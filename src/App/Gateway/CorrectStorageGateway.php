<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Gateway;


use App\Common\Constants;
use App\Entity\CorrectStorage;
use App\Entity\PriceInterval;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Literal;
use Zend\Db\Sql\Select;
use Zend\Hydrator\HydratorInterface;


class CorrectStorageGateway extends AbstractGateway
{
    /**
     * @var CorrectStorage
     */
    protected $prototype;

    /**
     * @param AdapterInterface $adapter
     * @param HydratorInterface $hydrator
     * @param CorrectStorage $prototype
     */
    public function __construct(AdapterInterface $adapter,
        HydratorInterface $hydrator,
        CorrectStorage $prototype)
    {
        $this->adapter = $adapter;
        $this->hydrator = $hydrator;
        $this->prototype = $prototype;

        parent::__construct($adapter, $hydrator);
    }

    /**
     * @param CorrectStorage $price
     * @return int Last insert value
     */
    public function insert(CorrectStorage $price)
    {
        $this->getTableGateway()->insert($this->getHydrator()->extract($price));
        return $this->getTableGateway()->getLastInsertValue();
    }

    /**
     * @param CorrectStorage $price
     * @return int affected rows
     */
    public function update(CorrectStorage $price)
    {
        return $this->getTableGateway()->update($this->getHydrator()->extract($price));
    }

    /**
     *
     * @param CorrectStorage ...$prices
     *
     * @return int number of rows
     * @throws \Exception
     */
    public function insertBulk(CorrectStorage ...$prices)
    {
        $counter = 0;
        try {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->beginTransaction();
            foreach ($prices as $price) {
                $this->getTableGateway()->insert($this->getHydrator()->extract($price));
                $counter++;
            }
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->commit();
        } catch (\Exception $exception) {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->rollback();
            throw $exception;
        }
        return $counter;
    }

    /**
     * @param CorrectStorage ...$prices
     * @return int affected rows
     * @throws \Exception
     */
    public function updateBulk(CorrectStorage ...$prices)
    {
        $counter = 0;
        try {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->beginTransaction();
            foreach ($prices as $price) {
                $counter += $this->getTableGateway()->update($this->getHydrator()->extract($price), ['date' => $price->getDate()]);
            }
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->commit();
        } catch (\Exception $exception) {
            $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->rollback();
            throw $exception;
        }
        return $counter;

    }

    public function delete($priceDate)
    {
        return $this->getTableGateway()->delete(['date' => $priceDate]);
    }

    public function deleteBulk(... $priceDates)
    {
        return $this->getTableGateway()->delete(['date' => $priceDates]);
    }

    /**
     * Due to aggregation we can't handle pagination in traditional way
     * But we still can provide pagination using concrete 'date' as reference point
     *
     * @param null $date
     * @param $limit
     * @param PriceInterval $intervalPrototype
     * @return \Zend\Db\ResultSet\HydratingResultSet
     */
    public function fetchPaginatedInterval($date, $limit, PriceInterval $intervalPrototype)
    {
        $select = new Select($this->getTable());

        // use sequential record number as rank
        // required for price intervals grouping
        $select->columns([
                'date',
                'price',
                'rank' => new Literal('@rank := @rank + 1')
            ])->order(['price' => 'ASC','date' => 'ASC'])
            ->limit(CorrectStorage::MAX_LENGTH_INTERVAL)
            ->offset(0)
            ->where(['date >= ?' => $date])
            ->getSqlString($this->getTableGateway()->getAdapter()->getPlatform())
        ;
        // group prices by intervals
        // prices with equal (date - rank) belongs to same interval
        $selectMain = new Select();
        $selectMain->from([$this->getTable() => $select])
            ->columns([
                'price',
                'date_start' => new Literal('MIN(date)'),
                'date_end' => new Literal('MAX(date)')
            ])
        ->group(new Literal('DATE_SUB(date, INTERVAL rank DAY), price'))
        ->order(['date_start' => 'ASC']);

        // initialize ranking var
        $sql = $this->getTableGateway()->getSql()->buildSqlString(
            (new Select())->columns([
                new Literal('@rank := 0')
            ]));
        $this->getTableGateway()->getAdapter()->getDriver()->getConnection()->execute($sql);
        $sql = $this->getTableGateway()->getSql()->buildSqlString($selectMain);
        $statement = $this->getTableGateway()->getAdapter()->getDriver()->createStatement($sql);
        $resultSet = new HydratingResultSet($this->getHydrator(), $intervalPrototype);
        $queryResult = $statement->execute();

        //use only limit records
        $array = [];
        while ($queryResult->count() && $queryResult->valid() && $limit-- > 0) {
            $array[] = $queryResult->current();
            $queryResult->next();
        }

        /** @var HydratingResultSet $resultSet */
        $resultSet = $resultSet->initialize($array);

        /** @var PriceInterval $item */
        foreach ($resultSet as $item) {
            $startDate = \DateTime::createFromFormat(Constants::DATE_FORMAT_PHP, $item->getDateStart());
            $endDate = \DateTime::createFromFormat(Constants::DATE_FORMAT_PHP, $item->getDateEnd());
            while ($startDate <= $endDate) {
                // skip day in range if day of week not selected
                $item->setDow(...[$startDate->format('w') => true]);
                $startDate->modify('+1 day');
            }
        }

        return $resultSet;
    }

    /**
     * Helper for paginated interval
     * We are required min/max date values because we've use them as reference point
     *
     * @return array ['min' => ... , 'max' => ... ]
     */
    public function fetchMinMaxDate()
    {
        $select = new Select($this->getTable());
        $select->columns([
                'min' => new Literal('MIN(date)'),
                'max' => new Literal('MAX(date)'),
                ]);

        $sql = $this->getTableGateway()->getSql()->buildSqlString($select);
        $statement = $this->getTableGateway()->getAdapter()->getDriver()->createStatement($sql);
        $result = $statement->execute();

        return $result->current();
    }

    /**
     * @param $date
     * @return CorrectStorage|null
     */
    public function fetchByDate($date): ?CorrectStorage
    {
        /** @var HydratingResultSet $resultSet */
        $resultSet = $this->getTableGateway()->select(['date' => $date]);
        if ($resultSet->count()) {
            /** @var CorrectStorage $price */
            $price = $resultSet->current();
        } else {
            $price = null;
        }
        return $price;
    }

    /**
     * @param $serializedPriceIntervalInfo
     * @return \Zend\Db\ResultSet\ResultSetInterface
     */
    public function fetchByPriceIntervalInfo($serializedPriceIntervalInfo)
    {
        //@todo fast solution to replace DTO
        //instead better to use DTO/ValueObject with own serialization/deserialization methods
        $info = explode(Constants::DATE_RANGE_DELIMITER, $serializedPriceIntervalInfo);

        $select = new Select($this->getTable());
        $select->where([
            'date >= ?' => $info[0],
            'date <= ?' => $info[1],
            'price = ?' => $info[2],
        ]);

        return $this->getTableGateway()->selectWith($select);
    }

    /**
     * @param CorrectStorage $price
     * @param $priceValue
     * @return int
     */
    public function updateWith(CorrectStorage $price, $priceValue)
    {
        $price->setPrice($priceValue);

        return $this->getTableGateway()->update($this->getHydrator()->extract($price), ['date' => $price->getDate()]);
    }

    /**
     * @return CorrectStorage
     */
    protected function getPrototype()
    {
        return $this->prototype;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return 'correct_storage';
    }
}