<?php

namespace Gam6itko\MultibaseBundle\Doctrine;

trait ConnectionSwitcherAwareTrait
{
    /** @var ConnectionSwitcher */
    protected $connectionSwitcher;

    public function setConnectionSwitcher(ConnectionSwitcher $connectionSwitcher)
    {
        $this->connectionSwitcher = $connectionSwitcher;
    }
}
