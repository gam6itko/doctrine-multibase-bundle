<?php

namespace Gam6itko\MultibaseBundle\Command\Database;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Gam6itko\MultibaseBundle\Doctrine\ConnectionSwitcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Wrapper of Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand.
 * Switch connection to `db-name-prefix` instance and creates database schema
 */
class CreateCommand extends Command
{
    protected static $defaultName = 'multibase:database:create';

    /** @var ManagerRegistry */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct();
        $this->managerRegistry = $managerRegistry;
    }

    protected function configure()
    {
        $this
            ->addArgument('connection', InputArgument::REQUIRED, 'The connection prototype to use for this command')
            ->addArgument('db-name-prefix', InputArgument::REQUIRED, 'Prefix for connection database instance')
            ->addArgument('instance-name', InputArgument::REQUIRED, 'Suffix for database instance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connectionName = $input->getArgument('connection');
        if (true === empty($connectionName)) {
            $connectionName = $this->managerRegistry->getDefaultConnectionName();
        }
        /** @var Connection $connectionPrototype */
        $connectionPrototype = $this->managerRegistry->getConnection($connectionName);
        $params = $connectionPrototype->getParams();
        // удаляем название БД, т.к. если ее не будет, то мы получим исключение
        unset($params['dbname']);

        $name = ConnectionSwitcher::buildInstanceName($input->getArgument('db-name-prefix'), $input->getArgument('instance-name'));

        $tmpConnection = DriverManager::getConnection($params);

        if (in_array($name, $tmpConnection->getSchemaManager()->listDatabases())) {
            $output->writeln(sprintf('<info>Database <comment>%s</comment> for connection named <comment>%s</comment> already exists. Skipped.</info>', $name, $connectionName));

            return 0;
        }

        $tmpConnection->getSchemaManager()->createDatabase($name);
        $tmpConnection->close();

        return 0;
    }
}
