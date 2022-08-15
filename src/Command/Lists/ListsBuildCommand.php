<?php

namespace PvListManager\Command\Lists;

use Symfony\Component\Console\Attribute\AsCommand;
use PvListManager\Command\AbstractCommand;
use PvListManager\Enum\OutputSymbols;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;


#[AsCommand(name: 'lists:build')]
class ListsBuildCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            //->setName('current-lists')
            ->setDescription('Builds the aggregate lists from the current configuration')
            ->setHelp(
                <<<EOT
<info>The lists:build command builds the aggregate lists from the current defined configuration</info>
<comment>TODO: pull/cache current version for comparison
    build pull/cache for block lists
    build pull/cache for allow lists
    build function for putting block/allow list contents into aggregate list
    build output capability for writing/checking section</comment>
EOT
            )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        //TO DO: create sections for output
        //put table in first section
        //begin processing of lists in second section

        $this->io->title("Build Lists");
        $this->io->text("<comment>Builds the aggregate lists from the current configuration</>");

        $set = $this->settings;

        $fs = new Filesystem();

        $table = $this->io->createTable();

        $table->setHeaders(['list type', 'list category', 'list name', 'exists', 'enabled', 'source']);
        $table->addRows([
            ['generated',
            'allow',
            $set->getGeneratedListName('allow'),
            (int)($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('allow'))),
            OutputSymbols::Dash->getString(),
            OutputSymbols::Dash->getString()
            ]
        ]);

        $table->addRows([
            ['generated',
            'block',
            $set->getGeneratedListName('block'),
            (int)($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('block'))),
            OutputSymbols::Dash->getString(),
            OutputSymbols::Dash->getString()
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
