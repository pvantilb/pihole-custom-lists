<?php

namespace PvListManager\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:current-list')]
class CurrentListCommand extends Command
{
    private $io;

    public function __construct()
    {
        parent::__construct();
    }

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

        $output->writeln(
            <<<EOT
<info>PV List Manager - Peter's Pi-Hole list manager app</info>
<comment>This is a simple cli app for managing/colsolidating adlists for easier pi-hole management.</comment>
EOT
        );

        return Command::SUCCESS;
    }
}
