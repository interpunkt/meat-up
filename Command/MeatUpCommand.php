<?php

namespace Ip\MeatUp\Command;

use Ip\MeatUp\Generator\CrudGeneratorBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MeatUpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('ip:meat-up')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a FormType, Controller and Twig files from your entitiy')

            ->addArgument(
                'classname',
                InputArgument::REQUIRED,
                'The classname of the entity.'
            )

            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force to overwrite existing entries in the lock file'
            )

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('TBD')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $className = $input->getArgument('classname');
        $output->writeln('Meat up: ' . $className);

        $hasForce = !empty($input->getOption('force'));

        $crudGenerator = CrudGeneratorBuilder::create()
            ->setClassName($className)
            ->setContainer($this->getContainer())
            ->setOutput($output)
            ->setHasForce($hasForce)
            ->build();

        if ($crudGenerator->generate()) {
            $output->writeln('Successfully created CRUD components');
        }
        else {
            $output->writeln('Error while creating CRUD components');
        }
    }
}