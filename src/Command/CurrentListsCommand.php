<?php

namespace PvListManager\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use PvListManager\Command\AbstractCommand;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;


#[AsCommand(name: 'app:current-lists')]
class CurrentListsCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            //->setName('current-lists')
            ->setDescription('Shows information about the current lists')
            ->setHelp(
                <<<EOT
<info>The current list command shows information about the current lists configuration</info>
<comment>command to be built and details filled out</comment>
EOT
            )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->io->title("Current Lists");
        $this->io->text("<comment>Currently defined lists for pi-hole are below</>");
        /*$output->writeln(
            <<<EOT
<info></info>
<comment>This is a simple cli app for managing/colsolidating adlists for easier pi-hole management.</comment>
EOT
        );*/

        $set = $this->settings;

        $fs = new Filesystem();

        $table = $this->io->createTable();

        $table->setHeaders(['list type', 'list category', 'list name', 'exists', 'enabled', 'source']);
        $table->addRows([
            ['generated',
            'allow',
            $set->getGeneratedListName('allow'),
            (int)($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('allow'))),
            '-',
            '-'
            ]
        ]);
        $table->addRows([
            ['generated',
            'block',
            $set->getGeneratedListName('block'),
            (int)($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('block'))),
            '-',
            '-'
            ]
        ]);

        $table->addRow(new TableSeparator());

        foreach($set->getBlockLists() as $bl)
        {
            $table->addRows([
                ['source',
                'block',
                $bl['name'],
                '-',
                (int)$bl['enabled'],
                $bl['source']
                ]
            ]);
        }

        $table->addRow(new TableSeparator());

        foreach($set->getAllowLists() as $all)
        {
            $table->addRows([
                ['source',
                'allow',
                $all['name'],
                '-',
                (int)$all['enabled'],
                $all['source']
                ]
            ]);
        }

        $table->setStyle('box');
        $table->render();

        /*if($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('allow')))
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

        foreach($set->getBlockLists(true) as $bl)
        {
            //var_dump($bl);
            $this->io->text("List item: " . $bl['name'] . " :: [" . $bl['source'] . "]");
        }*/

        return AbstractCommand::SUCCESS;
    }
}
