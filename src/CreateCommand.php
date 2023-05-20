<?php

namespace ResalePanterBot;

use Discord\Builders\CommandBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Option;
use Exception;
use React\Promise\ExtendedPromiseInterface;

class CreateCommand
{
    /**
     * @param Discord  $discord
     * @param string   $name
     * @param string   $description
     * @param Option[] $options
     * @return ExtendedPromiseInterface
     * @throws Exception
     */
    public static function create(Discord $discord, string $name, string $description, array $options = []): ExtendedPromiseInterface
    {
        $command = CommandBuilder::new()
            ->setName($name)
            ->setDescription($description);

        foreach ($options as $option) {
            $command->addOption($option);
        }

        $promise = $discord->application->commands->save(
            $discord->application->commands->create(
                $command->toArray()
            )
        );

        echo "\033[0m\033[48;2;0;128;0m Command created: $name \033[0m", PHP_EOL;

        return $promise;
    }
}