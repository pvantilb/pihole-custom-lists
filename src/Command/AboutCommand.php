<?php

namespace PvListManager\Command;

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
            ->setDescription('pv-list-manager allows for cli management of lists for pihole')
            ->setHelp(
                <<<EOT
<info>>pv-list-manager help about</info>
<comment>The pv-list-manager cli app is for managing a custom list for pi-hole DNS sinkhole.
    It allows for defining and aggregating multiple sources into a single master that you
    can then configure pi-hole to use to reduce clutter in the list management screen.</comment>
EOT
            )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln(
            <<<EOT
<info>PV List Manager - Peter's Pi-Hole list manager app</info>
<comment>The pv-list-manager cli app is for managing a custom list for pi-hole DNS sinkhole.
It allows for defining and aggregating multiple sources into a single master that you
can then configure pi-hole to use to reduce clutter in the list management screen.</comment>
EOT
        );

        return Command::SUCCESS;
    }
}
