<?php

namespace Gam6itko\MultibaseBundle\Command\Schema;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Gam6itko\MultibaseBundle\Command\Schema\Traits\RequiredArgumentsTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends UpdateSchemaDoctrineCommand
{
    use RequiredArgumentsTrait;

    protected static $defaultName = 'multibase:schema:update';

    protected function configure()
    {
        $this
            ->addOption('dump-sql', null, InputOption::VALUE_NONE, 'Instead of trying to apply generated SQLs into EntityManager Storage Connection, output them.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, "Don't ask for the deletion of the database, but force the operation to run.")
            ->addOption('complete', null, InputOption::VALUE_NONE, 'If defined, all assets of the database which are not relevant to the current metadata will be dropped.');
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
