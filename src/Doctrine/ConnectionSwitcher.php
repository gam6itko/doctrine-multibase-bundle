<?php

namespace Gam6itko\MultibaseBundle\Doctrine;

use Doctrine\DBAL\Connection;
use Gam6itko\MultibaseBundle\Event\PostSwitchEvent;
use Gam6itko\MultibaseBundle\Event\PreSwitchEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Switch abstract doctrine connection to real Database connection with specified prefix
 */
class ConnectionSwitcher
{
    /** @var Connection */
    protected $connection;

    /** @var string */
    protected $dbNamePrefix;

    /** @var string */
    protected $currentInstanceSuffix;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function dispatch($event)
    {
        if (null === $this->eventDispatcher) {
            return null;
        }

        return $this->eventDispatcher->dispatch($event);
    }

    /**
     * DoctrineEmConnectionSwitcher constructor.
     */
    public function __construct(Connection $connectionPrototype, string $dbNamePrefix)
    {
        $this->connection = $connectionPrototype;
        $this->dbNamePrefix = $dbNamePrefix;
    }

    public function getCurrentInstanceSuffix(): string
    {
        return $this->currentInstanceSuffix;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public static function buildInstanceName(string $dbNamePrefix, string $dbSuffix)
    {
        return $dbNamePrefix.'_'.$dbSuffix;
    }

    /**
     * @return ConnectionSwitcher
     */
    public function switchTo(string $dbSuffix)
    {
        $connection = $this->connection;
        $params = $connection->getParams();

        $dbName = $this->buildInstanceName($this->dbNamePrefix, $dbSuffix);
        if ($params['dbname'] === $dbName) {
            return $this;
        }

        $this->dispatch(new PreSwitchEvent($connection, $dbSuffix));

        $connection->close();

        $params['dbname'] = $dbName;

        $connection->__construct(
            $params,
            $connection->getDriver(),
            $connection->getConfiguration(),
            $connection->getEventManager()
        );

        $connection->connect();

        $this->currentInstanceSuffix = $dbSuffix;

        $this->dispatch(new PostSwitchEvent($connection, $dbSuffix));

        return $this;
    }
}
