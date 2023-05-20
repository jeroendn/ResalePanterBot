<?php

namespace ResalePanterBot;

use Discord\Discord;
use Discord\Parts\Interactions\Command\Command;
use Discord\Repository\Interaction\GlobalCommandRepository;
use Exception;
use React\Promise\PromiseInterface;

final class DeleteCommands
{
    /**
     * @param Discord $discord
     * @return PromiseInterface
     * @throws Exception
     */
    public static function delete(Discord $discord): PromiseInterface
    {
        return $discord->application->commands->freshen()->then(function (GlobalCommandRepository $commands) {
            /** @var Command $command */
            foreach ($commands as $command) {
                $commands->delete($command);

                echo "\033[0m\033[31m Command deleted: $command->name \033[0m", PHP_EOL;
            }
        });
    }
}