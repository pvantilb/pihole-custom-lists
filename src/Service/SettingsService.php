<?php


namespace PvListManager\Service;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use PvListManager\Application;
use PvListManager\Config\SettingsConfiguration;
use StringBackedEnum;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class SettingsService
{
    /**
     * The path to the configuration file.
     *
     * @var string
     */
    private $configurationFilePath;

    /**
     * The file system.
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * The configuration options.
     *
     * @var Array
     */
    private $options;

    private $optionsArray;

    private $hasProcessed = false;

    /**
     * Constructor.
     */
    public function __construct(string $configurationFilePath, Filesystem $filesystem)
    {
        $this->configurationFilePath = $configurationFilePath;
        $this->filesystem = $filesystem;
        $this->options = $this->load($this->configurationFilePath);
    }

    /**
     * Save the options back to the configuration file when we're destroying the object.
     */
    public function __destruct()
    {
        // do not do write
        // if($this->doWrite)
        // {
        //     $this->filesystem->dumpFile($this->configurationFilePath, (string) Yaml::dump($this->options, 3, 2));
        // }
    }

    public function validateConfig()
    {
        $processor = new Processor();
        $listManagerConfig = new SettingsConfiguration();

        try {
            $processedConfig = $processor->processConfiguration($listManagerConfig, $this->optionsArray);
            //var_dump($processedConfig);
            $this->hasProcessed = true;
            $this->optionsArray = $processedConfig;
        } catch(\Throwable $th)
        {
            throw new InvalidArgumentException(sprintf('Error in configuration: %s', $th->getMessage()),$th->getCode(), $th);
        }
    }

    /**
     * Get the storage location for lists.
     */
    public function getStorageLocation(): string
    {
        return $this->optionsArray['list_storage_folder'];
    }

    public function getGeneratedListName($list = 'allow'): string
    {
        $rtn = '';

        switch ($list) {
            case 'allow':
                $rtn = $this->optionsArray['generated_lists']['allowlist'];
                break;
            case 'block':
                $rtn = $this->optionsArray['generated_lists']['blocklist'];
                break;
            default:
                //default case it to always return allow list
                $rtn = $this->optionsArray['generated_lists']['allowlist'];
                break;
        }

        return $rtn;
    }

    public function getBlockLists($filterEnabled = false): array
    {
        $rtn = [];

        if($filterEnabled)
        {
            foreach($this->optionsArray['block_sources'] as $bl)
            {
                if($bl['enabled'])
                {
                    $rtn = array_merge($rtn, [$bl]);
                }
            }
        } else {
            $rtn = $this->optionsArray['block_sources'];
        }
        return $rtn;
    }

    public function getConfigParamCount(): int
    {
        return count((array)$this->options);
        //return 0;
    }

    public function getOptions(bool $asArray = false)
    {
        if($asArray)
        {
            return $this->optionsArray;
        }
        else
        {
            return $this->options;
        }
    }

    /**
     * Load the options from the configuration file.
     */
    private function load(string $configurationFilePath)
    {
        $configuration = [];

        if ($this->filesystem->exists($configurationFilePath)) {
            //var_dump($configurationFilePath);
            $fileContentsString = file_get_contents($configurationFilePath);
            $configuration = Yaml::parse($fileContentsString, Yaml::PARSE_OBJECT_FOR_MAP);
            $this->optionsArray = Yaml::parse($fileContentsString);
            //var_dump($configuration);
        }

        return $configuration;
    }
}