<?php

namespace Gam6itko\MultibaseBundle\Event;

use Doctrine\DBAL\Connection;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractSwitcherEvent extends Event
{
    /**
     * Before connection switch
     *
     * @Event("Gam6itko\MultibaseBundle\Event\ConnectionSwitcherEvent")
     */
    const PRE_SWITCH = 'connection_switcher.pre_switch';

    /**
     * After connection switch
     *
     * @Event("Gam6itko\MultibaseBundle\Event\ConnectionSwitcherEvent")
     */
    const POST_SWITCH = 'connection_switcher.post_switch';

    /** @var Connection */
    protected $connection;

    /** @var string */
    protected $suffix;

    public function __construct(Connection $connection, string $suffix)
    {
        $this->connection = $connection;
        $this->suffix = $suffix;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }
}
