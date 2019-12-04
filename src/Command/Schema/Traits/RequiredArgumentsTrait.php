<?php

namespace Gam6itko\MultibaseBundle\Command\Schema\Traits;

use Doctrine\ORM\EntityManager;
use Gam6itko\MultibaseBundle\Doctrine\ConnectionSwitcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

trait RequiredArgumentsTrait
{
    protected function addRequiredArguments(Command $command)
    {
        $command
            ->addArgument('em', InputArgument::REQUIRED, 'The connection prototype to use for this command')
            ->addArgument('db-name-prefix', InputArgument::REQUIRED, 'Prefix for connection database instance')
            ->addArgument('instance-name', InputArgument::REQUIRED, 'Suffix for database instance');
    }

    protected function switchEm(Command $command, InputInterface $input)
    {
        $command->getDefinition()->addOption(new InputOption('em'));
        $input->setOption('em', $input->getArgument('em'));

        /** @var EntityManager $em */
        $em = $command->getApplication()->getKernel()->getContainer()->get('doctrine')->getManager($input->getArgument('em'));
        $switcher = new ConnectionSwitcher($em->getConnection(), $input->getArgument('db-name-prefix'));
        $switcher->switchTo($input->getArgument('instance-name'));
    }
}
