<?php

namespace Gam6itko\MultibaseBundle\Command\Schema;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DropSchemaDoctrineCommand;
use Gam6itko\MultibaseBundle\Command\Schema\Traits\RequiredArgumentsTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DropCommand extends DropSchemaDoctrineCommand
{
    use RequiredArgumentsTrait;

    protected static $defaultName = 'multibase:schema:drop';

    protected function configure()
    {
        $this
            ->setDescription('Drop the complete database schema of EntityManager Storage Connection or generate the corresponding SQL output')
            ->addOption('dump-sql', null, InputOption::VALUE_NONE, 'Instead of trying to apply generated SQLs into EntityManager Storage Connection, output them.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, "Don't ask for the deletion of the database, but force the operation to run.")
            ->addOption('full-database', null, InputOption::VALUE_NONE, 'Instead of using the Class Metadata to detect the database table schema, drop ALL assets that the database contains.');
        $this->addRequiredArguments($this);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->switchEm($this, $input);

        return parent::execute($input, $output);
    }
}
