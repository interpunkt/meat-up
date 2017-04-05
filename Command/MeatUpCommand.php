<?php


namespace DL\MeatUp\Command;


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
            ->setDescription('Creates a FormType, standard Controller and Twig files from an entitiy')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('TBD');

        $this
            ->addArgument('classname', InputArgument::REQUIRED, 'The classname of the entity.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Meat up: ' . $input->getArgument('classname'));
    }
}