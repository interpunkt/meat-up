<?php


namespace DL\MeatUp\Command;

use DL\MeatUp\Generator\CrudGeneratorFactory;
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

        $appDir = $this->getContainer()->getParameter('kernel.root_dir');
        $output->writeln('App dir: ' . $appDir);

        $bundles = $this->getContainer()->get('kernel')->getBundles();

        $meatUpDir =  realpath(dirname(__FILE__, 2));

        $bundleRootDir = null;
        $bundleNameSpace = null;

        foreach ($bundles as $bundle) {
            if (strpos($className, $bundle->getNamespace(), 0) === 0) {
                $bundleNameSpace = $bundle->getNamespace();
                $bundleRootDir = $bundle->getPath();
                break;
            }
        }

        if (is_null($bundleRootDir) || is_null($bundleNameSpace) ) {
            $output->writeln('Can\'t find bundle of Entity');
            return 1;
        }

        $crudGenerator = CrudGeneratorFactory::create()
            ->setClassName($className)
            ->setAppDir($appDir)
            ->setMeatUpDir($meatUpDir)
            ->setEntityBundleNameSpace($bundleNameSpace)
            ->setBundleRootDir($bundleRootDir)
            ->setOutput($output)
            ->build();

        $ret = $crudGenerator->generate();

        $output->writeln($ret);
    }
}