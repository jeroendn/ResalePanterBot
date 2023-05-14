<?php

namespace ResalePanterBot\Integration\PingPong;

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Interaction;
use Exception;
use ResalePanterBot\Integration\CommandInterface;
use ResalePanterBot\Integration\IntegrationInterface;
use Throwable;

final class PingPong implements CommandInterface, IntegrationInterface
{
    public const COMMAND_NAME = 'ping';
    public const COMMAND_INFO = 'Check bot online status';

    private Discord $discord;

    /**
     * @param Discord $discord
     */
    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    /**
     * @return string
     */
    public function getHelpString(): string
    {
        return '/' . self::COMMAND_NAME . "\n" . self::COMMAND_INFO;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function registerCommands(): void
    {
        $this->discord->application->commands->save(
            $this->discord->application->commands->create(CommandBuilder::new()
                ->setName(self::COMMAND_NAME)
                ->setDescription(self::COMMAND_INFO)
                ->toArray()
            )
        );
    }

    /**
     * @param Interaction $interaction
     * @return void
     */
    public function handleCommand(Interaction $interaction): void
    {
        $message = MessageBuilder::new()->setContent('pong');

        try {
            $interaction->respondWithMessage($message);
        }
        catch (Throwable $e) {
            echo $e->getMessage();
        }
    }
}