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

namespace PvListManager\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use PvListManager\Service\SettingsService;
use PvListManager\Service\FetchService;

abstract class AbstractCommand extends Command
{
    /**
     * The global Ymir CLI configuration.
     *
     * @var SettingsService
     */
    protected $settings;
    protected $io;
    protected $fetch;


    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * Constructor.
     */
    public function __construct(SettingsService $setSvc, FetchService $fetchSvc)
    {
        $this->settings = $setSvc;
        $this->fetch = $fetchSvc;

        //not using custom input definition
        //$this->setDefinition(new InputDefinition());

        //$this->configure();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    //protected function execute(InputInterface $input, SymfonyOutputInterface $output)
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->isInteractive() && $this->mustBeInteractive()) {
            throw new RuntimeException(sprintf('Cannot run "%s" command in non-interactive mode', $input->getFirstArgument()));
        }

        //return $this->perform($input, new Output($input, $output)) ?? Command::SUCCESS;
        return parent::execute($input, $output);
    }

    /**
     * Get the value of an argument that should be an array.
     */
    protected function getArrayArgument(InputInterface $input, string $argument, bool $requiredNonInteractive = true): array
    {
        $value = $input->getArgument($argument);

        if (null === $value && $requiredNonInteractive && !$input->isInteractive()) {
            throw new InvalidArgumentException(sprintf('You must pass a "%s" argument when running in non-interactive mode', $argument));
        } elseif (null !== $value && !is_array($value)) {
            throw new InvalidArgumentException(sprintf('The "%s" argument must be an array value', $argument));
        }

        return (array) $value;
    }

    /**
     * Get the value of a option that should be an array.
     */
    protected function getArrayOption(InputInterface $input, string $option): array
    {
        $value = [];

        if ($input->hasOption($option)) {
            $value = $input->getOption($option);
        }

        if (!is_array($value)) {
            throw new InvalidArgumentException(sprintf('The "--%s" option must be an array', $option));
        }

        return $value;
    }

    /**
     * Get the value of a option that should be boolean.
     */
    protected function getBooleanOption(InputInterface $input, string $option): bool
    {
        return $input->hasOption($option) && $input->getOption($option);
    }

    /**
     * Get the value of an argument that should be numeric.
     */
    protected function getNumericArgument(InputInterface $input, string $argument, bool $requiredNonInteractive = true): int
    {
        $value = $input->getArgument($argument);

        if (null === $value && $requiredNonInteractive && !$input->isInteractive()) {
            throw new InvalidArgumentException(sprintf('You must pass a "%s" argument when running in non-interactive mode', $argument));
        } elseif (null !== $value && !is_numeric($value)) {
            throw new InvalidArgumentException(sprintf('The "%s" argument must be a numeric value', $argument));
        }

        return (int) $value;
    }

    /**
     * Get the value of a option that should be numeric. Returns null if not present.
     */
    protected function getNumericOption(InputInterface $input, string $option): ?int
    {
        $value = null;

        if ($input->hasOption($option)) {
            $value = $input->getOption($option);
        }

        if (null === $value) {
            return $value;
        } elseif (is_array($value) || !is_numeric($value)) {
            throw new InvalidArgumentException(sprintf('The "--%s" option must be a numeric value', $option));
        }

        return (int) $value;
    }

    /**
     * Get the value of an argument that should be a string.
     */
    protected function getStringArgument(InputInterface $input, string $argument, bool $requiredNonInteractive = true): string
    {
        $value = $input->getArgument($argument);

        if (null === $value && $requiredNonInteractive && !$input->isInteractive()) {
            throw new InvalidArgumentException(sprintf('You must pass a "%s" argument when running in non-interactive mode', $argument));
        } elseif (null !== $value && !is_string($value)) {
            throw new InvalidArgumentException(sprintf('The "%s" argument must be a string value', $argument));
        }

        return (string) $value;
    }

    /**
     * Get the value of a option that should be a string. Returns null if not present.
     */
    protected function getStringOption(InputInterface $input, string $option, bool $requiredNonInteractive = false): ?string
    {
        $value = null;

        if ($input->hasOption($option)) {
            $value = $input->getOption($option);
        }

        if (null === $value && $requiredNonInteractive && !$input->isInteractive()) {
            throw new InvalidArgumentException(sprintf('You must use the "--%s" option when running in non-interactive mode', $option));
        } elseif (null !== $value && !is_string($value)) {
            throw new InvalidArgumentException(sprintf('The "--%s" option must be a string value', $option));
        }

        return $value;
    }

    /**
     * Invoke another console command.
     */
    //protected function invoke(SymfonyOutputInterface $output, string $command, array $arguments = []): int
    protected function invoke(OutputInterface $output, string $command, array $arguments = []): int
    {
        $application = $this->getApplication();

        if (!$application instanceof Application) {
            throw new RuntimeException('No Application instance found');
        }

        return $application->find($command)->run(new ArrayInput($arguments), $output);
    }

    /**
     * Whether the command must always be run in interactive mode or not.
     */
    protected function mustBeInteractive(): bool
    {
        return false;
    }

    /**
     * Wait for the given callable to complete.
     */
    protected function wait(callable $callable, int $timeout = 60, int $sleep = 1)
    {
        if (0 !== $timeout) {
            $timeout += time();
        }

        do {
            $result = $callable();
            sleep($sleep);
        } while (empty($result) && time() < $timeout);

        return $result;
    }

}
