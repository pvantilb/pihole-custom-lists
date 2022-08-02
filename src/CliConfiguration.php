<?php

declare(strict_types=1);

/*
 * This file is part of Ymir command-line tool.
 *
 * (c) Carl Alexander <support@ymirapp.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PvListManager;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class CliConfiguration
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

    /**
     * Control if config file is written everytime object is destroyed
     *
     * @var bool
     */
    private $doWrite = false;

    /**
     * Constructor.
     */
    public function __construct(string $configurationFilePath, Filesystem $filesystem)
    {
        $this->configurationFilePath = $configurationFilePath;
        $this->filesystem = $filesystem;
        $this->options = $this->load($configurationFilePath);
    }

    /**
     * Save the options back to the configuration file when we're destroying the object.
     */
    public function __destruct()
    {
        if($this->doWrite)
        {
            $this->filesystem->dumpFile($this->configurationFilePath, (string) Yaml::dump($this->options, 3, 2));
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
        return count($this->options);
        //return 0;
    }

    public function getOptions()
    {
        /*$rtn = '';

        if(is_iterable($this->options))
        {
            $rtn = '\n';

            foreach ($this->options as $k => $v) {
                $rtn .= 'Key: '. $k . ' - value: ' . $v;
            }
        }*/

        return $this->options;
    }

    /**
     * Get the CLI version on GitHub.
     */
    public function getGitHubCliVersion(): string
    {
        return (string) $this->get('github_cli_version');
    }

    /**
     * Get the timestamp when GitHub was last checked for a CLI update.
     */
    public function getGitHubLastCheckedTimestamp(): int
    {
        return (int) $this->get('github_last_checked');
    }

    /**
     * Check if the global configuration has an access token.
     */
    public function hasAccessToken(): bool
    {
        return !empty($this->getAccessToken());
    }

    /**
     * Set the access token in the global configuration file.
     */
    public function setAccessToken(string $token)
    {
        $this->set('token', $token);
    }

    /**
     * Set the active team ID in the global configuration file.
     */
    public function setActiveTeamId(int $teamId)
    {
        $this->set('active_team', $teamId);
    }

    /**
     * Set the CLI version on GitHub.
     */
    public function setGitHubCliVersion(string $version)
    {
        $this->set('github_cli_version', $version);
    }

    /**
     * Set the timestamp when GitHub was last checked for a CLI update.
     */
    public function setGitHubLastCheckedTimestamp(int $timestamp)
    {
        $this->set('github_last_checked', $timestamp);
    }

    /**
     * Get the given configuration option or return the default.
     */
    private function get(string $option, $default = null)
    {
        $val = '';
        try {
            $val = $this->options[$option];
        } catch (\Throwable $th) {
            //throw $th;
            throw new RuntimeException(sprintf('Error trying to get item "%s" with message %s', $option, $th->getMessage()));
        }

        return $val;
    }

    /**
     * Checks if the configuration has the given option.
     */
    private function has(string $option): bool
    {
        return $this->options[$option];
    }

    /**
     * Load the options from the configuration file.
     */
    private function load(string $configurationFilePath)
    {
        $configuration = [];

        if ($this->filesystem->exists($configurationFilePath)) {
            var_dump($configurationFilePath);
            $configuration = Yaml::parse(file_get_contents($configurationFilePath));
            var_dump($configuration);
        }

        return $configuration;
    }

    /**
     * Set the configuration option.
     */
    private function set(string $option, $value)
    {
        $this->options[$option] = $value;
    }
}
