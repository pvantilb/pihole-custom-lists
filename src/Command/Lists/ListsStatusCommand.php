<?php

namespace PvListManager\Command\Lists;

use PvListManager\Command\AbstractCommand;
use PvListManager\Enum\OutputSymbols;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;


#[AsCommand(name: 'lists:status')]
class ListsStatusCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setDescription('Shows status information the current lists')
            ->setHelp(
                <<<EOT
<info>The lists status command shows status information about the current lists</info>
EOT
            )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->io->title("Lists Status");
        $this->io->text("<comment>The lists status command shows status information about the current lists</>");

        $set = $this->settings;

        $fs = new Filesystem();

        $table = $this->io->createTable();

        $table->setHeaders(['list type',
            'list category',
            'list name',
            new TableCell('exists',['colspan' => 2, 'style' => new TableCellStyle(['align' => 'center'])]),
            new TableCell('isCurrent', ['colspan' => 2, 'style' => new TableCellStyle(['align' => 'center'])])
        ]);
        $table->addRows([
            ['generated',
            'allow',
            $set->getGeneratedListName('allow'),
            new TableCell(
                (int)($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('allow'))),
                ['colspan' => 2,
                    'style' => new TableCellStyle(['align' => 'center'])
                ]),
            new TableCell(
                '<TO DO>',
                ['colspan' => 2,
                    'style' => new TableCellStyle(['align' => 'center'])
                ])
            ]
        ]);

        $table->addRows([
            ['generated',
            'block',
            $set->getGeneratedListName('block'),
            new TableCell(
                (int)($fs->exists($set->getStorageLocation() . '/' . $set->getGeneratedListName('block'))),
                ['colspan' => 2,
                    'style' => new TableCellStyle(['align' => 'center'])
                ]),
            new TableCell(
                '<TO DO>',
                ['colspan' => 2,
                    'style' => new TableCellStyle(['align' => 'center'])
                ])
            ]
        ]);

        $table->addRow(new TableSeparator());
        $table->addRow(['list type',
            'list category',
            'list name',
            'enabled',
            'isCurrent',
            'isIncorporated',
            'source'
        ]);
        $table->addRow(new TableSeparator());

        foreach($set->getBlockLists() as $bl)
        {
            $table->addRows([
                ['source',
                'block',
                $bl['name'],
                (int)$bl['enabled'],
                '-',
                '-',
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
                (int)$all['enabled'],
                '-',
                '-',
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
