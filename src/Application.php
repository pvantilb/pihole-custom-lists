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

namespace Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    /**
     * Constructor.
     */
    public function __construct(iterable $commands)
    {
        parent::__construct('Console');

        foreach ($commands as $command) {
            $this->add($command);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderThrowable(\Throwable $exception, OutputInterface $output): void
    {
        /*if ($exception instanceof CommandCancelledException) {
            return;
        }*/

        parent::renderThrowable($exception, $output);
    }
}
