<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Gateway;


use App\Entity\PriceInterval;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\HydratorInterface;

class PriceIntervalGateway extends AbstractGateway
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;
    /**
     * @var PriceInterval
     */
    protected $prototype;

    /**
     * @param AdapterInterface $adapter
     * @param HydratorInterface $hydrator
     * @param PriceInterval $prototype
     */
    public function __construct(AdapterInterface $adapter,
                                HydratorInterface $hydrator,
                                PriceInterval $prototype)
    {
        $this->adapter = $adapter;
        $this->hydrator = $hydrator;
        $this->prototype = $prototype;

        parent::__construct($adapter, $hydrator);
    }

    /**
     * @param PriceInterval $priceInterval
     * @return int Last insert value
     */
    public function insert(PriceInterval $priceInterval)
    {

        $this->getTableGateway()->insert($this->getHydrator()->extract($priceInterval));
        return $this->getTableGateway()->getLastInsertValue();
    }

    /**
     * @return PriceInterval
     */
    protected function getPrototype() : PriceInterval
    {
        return $this->prototype;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return 'price_interval';
    }
}