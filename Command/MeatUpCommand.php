<?php

namespace Ip\MeatUp\Command;

use Ip\MeatUp\Generator\CrudGeneratorBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MeatUpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('dl:meat-up')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a FormType, Controller and Twig files from your entitiy')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('TBD');

        $this
            ->addArgument('classname', InputArgument::REQUIRED, 'The classname of the entity.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $className = $input->getArgument('classname');
        $output->writeln('Meat up: ' . $className);

        $crudGenerator = CrudGeneratorBuilder::create()
            ->setClassName($className)
            ->setContainer($this->getContainer())
            ->setOutput($output)
            ->build();

        if ($crudGenerator->generate())
        {
            $output->writeln('Successfully created CRUD components');
        }
        else
        {
            $output->writeln('Error while creating CRUD components');
        }
    }
}