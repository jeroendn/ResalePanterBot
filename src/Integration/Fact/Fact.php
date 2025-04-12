<?php

namespace ResalePanterBot\Integration\Fact;

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Guild;
use Discord\Parts\Interactions\Interaction;
use Discord\Repository\Guild\ChannelRepository;
use JetBrains\PhpStorm\NoReturn;
use ResalePanterBot\CreateCommand;
use ResalePanterBot\Integration\IntegrationInterface;

final class Fact implements IntegrationInterface
{
    public const COMMAND_NAME_RANDOM_FACT = 'random-fact';
    public const COMMAND_INFO_RANDOM_FACT = 'Get a random fact';

    private Discord $discord;

    /**
     * @param Discord $discord
     */
    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    public function registerCommands(): void
    {
        CreateCommand::create($this->discord, self::COMMAND_NAME_RANDOM_FACT, self::COMMAND_INFO_RANDOM_FACT);
    }

    public function handleRandomFactCommand(Interaction $interaction): void
    {
        $embed = new Embed($this->discord, [
            'title'       => 'Random fact',
            'description' => $this->getRandomFactMessage()
        ]);

        $message = MessageBuilder::new()->setEmbeds([$embed]);

        $interaction->respondWithMessage($message);
    }

    public function sendFactMessage(Discord $discord, bool $killProcess = false): void
    {
        $embed = new Embed($discord, [
            'title'       => 'Jory\'s feitje',
            'description' => $this->getRandomFactMessage()
        ]);

        $message = MessageBuilder::new()->setEmbeds([$embed]);

        if (getenv('MODE') === 'PROD') {
            $guildId = getenv('RESALE_PARTNERS_GUILD_ID');
        }
        else {
            $guildId = getenv('KOTS_KAT_GUILD_ID');
        }

        $discord->guilds->fetch($guildId)->then(function (Guild $guild) use ($discord, $message, $killProcess) {

            $discord->getFactory()->repository(ChannelRepository::class)->fetch($guild->system_channel_id)->then(function (Channel $channel) use ($discord, $message, $killProcess) {
                $channel->sendMessage($message)->finally(function () use ($killProcess) {
                    if ($killProcess) $this->stop();
                });
            }, function ($e) use ($killProcess) {
                echo "Failed to fetch channel: " . $e->getMessage() . PHP_EOL;
                if ($killProcess) $this->stop();
            });

        }, function ($e) use ($killProcess) {
            echo "Failed to fetch guild: " . $e->getMessage() . PHP_EOL;
            if ($killProcess) $this->stop();
        });
    }

    private function getRandomFactMessage(): string
    {
        $facts = json_decode(file_get_contents(__DIR__ . '/Config/facts.json'), true);

        return $facts[array_rand($facts)];
    }

    #[NoReturn] private function stop(): void
    {
        echo "Stopping job! \n";
        die;
    }
}