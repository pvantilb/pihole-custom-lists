<?php

namespace PvListManager\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use PvListManager\Command\AbstractCommand;
//use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PvListManager\Service\SettingsService;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(name: 'app:debug')]
class DebugCommand extends AbstractCommand
{
    private $io;

    /**
     * Undocumented variable
     *
     * @var SettingsService
     */
    protected $settings;

    public function __construct(SettingsService $setSvc)
    {
        parent::__construct($setSvc);
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
        $sets = $this->settings->getOptions(true);
        //var_dump($sets['list_manager']['block_sources']);

        //$output->writeln("There are " . count($sets['list_manager']['block_sources']) . " sources for block lists");

        //Utilize array access for various parameters
        //build access into getters in SettingsService for ease of use in commands
        foreach($sets['list_manager']['block_sources'] as $bls)
        {
            $output->writeln("Array Block List: " . $bls['name'] . " source [" . $bls['source'] . "]");
        }

        //$this->cliConfiguration->get('listStorage');
        //var_dump($this->cliConfiguration->getOptions());
        //$output->writeln(sprintf('Config Param: %s = %s', 'version', $this->application->get_cfg_var('version')));
        //$output->writeln(sprintf('Config Param: %s = %s', 'version', $this->application->get_cfg_var('cli_configuration_path')));


        return AbstractCommand::SUCCESS;
    }

}
