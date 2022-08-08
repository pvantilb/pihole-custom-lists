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

    /**
     * Constructor.
     */
    public function __construct(string $configurationFilePath, Filesystem $filesystem)
    {
        $this->configurationFilePath = $configurationFilePath;
        $this->filesystem = $filesystem;
        $this->options = $this->load($configurationFilePath);

        /*try {
            $this->validateConfig();
        } catch(\Throwable $th)
        {
            throw new InvalidArgumentException(sprintf('Error in configuration: %s', $th->getMessage()),$th->getCode(), $th);
        }*/

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
        } catch(\Throwable $th)
        {
            throw new InvalidArgumentException(sprintf('Error in configuration: %s', $th->getMessage()),$th->getCode(), $th);
        }
    }

    /**
     * Get the access token from the global configuration file.
     */
    public function getAccessToken(): string
    {
        $token = getenv('YMIR_API_TOKEN');

        if (!is_string($token)) {
            $token = (string) $this->get('token');
        }

        return $token;
    }

    public function getConfigParamCount(): int
    {
        return count((array)$this->options);
        //return 0;
    }

    public function getOptions(bool $asArray = false)
    {
        /*$rtn = '';

        if(is_iterable($this->options))
        {
            $rtn = '\n';

            foreach ($this->options as $k => $v) {
                $rtn .= 'Key: '. $k . ' - value: ' . $v;
            }
        }*/

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
     * Get the given configuration option or return the default.
     */
    private function get(string $option, $default = null)
    {
        $val = '';
        try {
            $val = $this->optionsArray[$option];
        } catch (\Throwable $th) {
            //throw $th;
            throw new RuntimeException(sprintf('Error trying to get item "%s" with message %s', $option, $th->getMessage()));
        }

        return $val;
    }

    /**
     * Checks if the configuration has the given option.
     */
    public function has(string $option): bool
    {
        return $this->optionsArray[$option];
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

    /**
     * Set the configuration option.
     */
    private function set(string $option, $value)
    {
        $this->optionsArray[$option] = $value;
    }
}