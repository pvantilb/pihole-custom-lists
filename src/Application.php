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

use PvListManager\Service\SettingsService;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\ErrorHandler\Debug;

class Application extends BaseApplication
{

    /**
     * Undocumented variable
     *
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * Constructor.
     */
    public function __construct(iterable $commands, string $version, SettingsService $settings)
    {

        Debug::enable();

        $this->settingsService = $settings;
        $this->settingsService->validateConfig();

        parent::__construct('PvListManager', $version);

        foreach ($commands as $command) {
            $this->add($command);
        }

    }

    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        //create new styles for use in output
        $output->getFormatter()->setStyle('test', 
            new OutputFormatterStyle('yellow', 'blue', ['bold', 'underscore']));

        parent::configureIO($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function renderThrowable(\Throwable $exception, OutputInterface $output): void
    {
        if ($exception instanceof \RuntimeException) {
            return;
        }
        //$this->render($exception, $output);

        parent::renderThrowable($exception, $output);
    }
}
