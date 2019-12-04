<?php

namespace Gam6itko\MultibaseBundle\Command\Schema;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Gam6itko\MultibaseBundle\Command\Schema\Traits\RequiredArgumentsTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends CreateSchemaDoctrineCommand
{
    use RequiredArgumentsTrait;

    protected static $defaultName = 'multibase:schema:create';

    protected function configure()
    {
        $this
            ->addOption('dump-sql', null, InputOption::VALUE_NONE, 'Instead of trying to apply generated SQLs into EntityManager Storage Connection, output them.');
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
