<?php

namespace PvListManager\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use PvListManager\Command\AbstractCommand;
//use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PvListManager\CliConfiguration;

#[AsCommand(name: 'app:debug')]
class DebugCommand extends AbstractCommand
{
    private $io;
    private $application;

    public function __construct(CliConfiguration $cliConfiguration)
    {
        parent::__construct($cliConfiguration);
    }

    protected function configure(): void
    {
        $this
            ->setName('debug')
            ->setDescription('debug operations for the application')
            ->setHelp(
                <<<EOT
<info>>pv-list-manager debug</info>
<comment>calling the main debug function will currently dump specified configuration fields</comment>
EOT
            )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('There are a total of %s config parameters set', $this->cliConfiguration->getConfigParamCount()));
        $output->writeln(sprintf('Lists will be stored here [%s]', $this->cliConfiguration->getOptions()['app.listStorage']));
        var_dump($this->cliConfiguration->getOptions());
        //$output->writeln(sprintf('Config Param: %s = %s', 'version', $this->application->get_cfg_var('version')));
        //$output->writeln(sprintf('Config Param: %s = %s', 'version', $this->application->get_cfg_var('cli_configuration_path')));


        return AbstractCommand::SUCCESS;
    }

}
