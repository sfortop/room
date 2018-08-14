<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Gateway;

use App\Entity\EntityInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\AbstractHydrator;
use Zend\Hydrator\HydratorInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

abstract class AbstractGateway implements TableGatewayBasedInterface
{
    protected $tableGateway;
    /**
     * @var AbstractHydrator
     */
    protected $hydrator;

    /**
     * @return EntityInterface
     */
    abstract protected function getPrototype();

    /**
     * AccountGateway constructor.
     * @param AdapterInterface $adapter
     * @param HydratorInterface $hydrator
     */
    public function __construct(AdapterInterface $adapter, HydratorInterface $hydrator)
    {
        $this->tableGateway = new TableGateway($this->getTable(), $adapter,null,
            new HydratingResultSet($hydrator, $this->getPrototype())
        );
        $this->hydrator = $hydrator;
    }

    /**
     * @return EntityInterface
     */
    public function create()
    {
        return clone $this->getPrototype();
    }

    /**
     * @param bool $paginated
     * @param null $select
     * @return \Zend\Db\ResultSet\ResultSet|Paginator
     * @throws \Exception
     */
    public function fetchAll($paginated = true, $select = null)
    {
        if ($paginated) {
            $adapter = $this->getTableGateway()->getAdapter();
            if ($adapter instanceof Adapter) {
                if (!$select) {
                    $select = new Select($this->getTableGateway()->getTable());
                }
                $dbSelect = new DbSelect(
                    $select,
                    $adapter,
                    $this->getTableGateway()->getResultSetPrototype()
                );
            } else {
                //@todo use specific exception
                throw new \Exception(sprintf('%s required but %s used as adapter', Adapter::class, get_class($adapter)));
            }
            $result = new Paginator($dbSelect);
        } else {
            $result = $this->getTableGateway()->select();
        }
        return $result;
    }

    /**
     * @param $data
     * @return object
     */
    public function createEntityFromArray($data)
    {
        /** @var HydratingResultSet $resultSet */
        $resultSet = $this->getTableGateway()->getResultSetPrototype();
        return $resultSet->getHydrator()->hydrate($data, clone $resultSet->getObjectPrototype());
    }

    /**
     * @return TableGateway
     */
    protected function getTableGateway() : TableGateway
    {
        return $this->tableGateway;
    }

    protected function getHydrator(): AbstractHydrator
    {
        return $this->hydrator;
    }
}