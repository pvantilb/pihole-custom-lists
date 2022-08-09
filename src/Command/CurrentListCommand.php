<?php

namespace PvListManager\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use PvListManager\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;


#[AsCommand(name: 'app:current-list')]
class CurrentListCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('current-list')
            ->setDescription('Shows information about the current list')
            ->setHelp(
                <<<EOT
<info>The current list commend shows information about the current aggregate list</info>
<comment>command to be built and details filled out</comment>
EOT
            )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->io->title("PV List Manager - Peter's Pi-Hole list manager app");
        $this->io->text("<comment>This is a simple cli app for managing/colsolidating adlists for easier pi-hole management.</>");
        /*$output->writeln(
            <<<EOT
<info></info>
<comment>This is a simple cli app for managing/colsolidating adlists for easier pi-hole management.</comment>
EOT
        );*/

        $set = $this->settings;

        $fs = new Filesystem();

        if($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('allow')))
        {
            $this->io->text("The generated allow list exists");
        } else {
            $this->io->warning("The generated allow list does NOT exist!");
            //$output->writeln("The generated allow list does NOT exist!");
        }

        if($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('block')))
        {
            $this->io->text("The generated block list exists");
        } else {
            //$output->writeln("The generated block list does NOT exist!");
            $this->io->text("The generated block list does NOT exist!");
        }

        return AbstractCommand::SUCCESS;
    }
}
