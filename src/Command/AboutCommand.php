<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:about')]
class AboutCommand extends Command
{
    private $io;

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('about')
            ->setDescription('Shows a short information about Composer.')
            ->setHelp(
                <<<EOT
<info>php composer.phar about</info>
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

        return 0;
    }
}
