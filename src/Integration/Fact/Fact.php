<?php

namespace ResalePanterBot\Integration\Fact;

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Guild;
use Discord\Parts\Interactions\Interaction;
use Discord\Repository\Guild\ChannelRepository;
use Exception;
use React\Promise\PromiseInterface;
use ResalePanterBot\CreateCommand;
use ResalePanterBot\Integration\IntegrationInterface;

final class Fact implements IntegrationInterface
{
    public const string COMMAND_NAME_RANDOM_FACT = 'random-fact';
    public const string COMMAND_INFO_RANDOM_FACT = 'Get a random fact';

    public function __construct(
        private readonly Discord $discord
    ) {}

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

    /**
     * @throws Exception
     */
    public function sendFactMessage(bool $killProcess = false): PromiseInterface
    {
        if (getenv('MODE') === 'PROD') {
            $guildId = getenv('RESALE_PARTNERS_GUILD_ID');
        }
        else {
            $guildId = getenv('KOTS_KAT_GUILD_ID');
        }

        return $this->discord->guilds->fetch($guildId)
            ->then(
                function (Guild $guild) use ($killProcess) {

                    return $this->discord->getFactory()->repository(ChannelRepository::class)->fetch($guild->system_channel_id)
                        ->then(
                            function (Channel $channel) use ($killProcess) {
                                $embed = new Embed($this->discord, [
                                    'title'       => 'Jory\'s feitje',
                                    'description' => $this->getRandomFactMessage()
                                ]);

                                $message = MessageBuilder::new()->setEmbeds([$embed]);

                                return $channel->sendMessage($message)->finally(function () use ($killProcess) {
                                    $this->discord->close();
                                });
                            },
                            function ($e) use ($killProcess) {
                                echo "Failed to fetch channel: " . $e->getMessage() . PHP_EOL;
                                $this->discord->close();
                            }
                        );

                },
                function ($e) use ($killProcess) {
                    echo "Failed to fetch guild: " . $e->getMessage() . PHP_EOL;
                    $this->discord->close();
                }
            );
    }

    private function getRandomFactMessage(): string
    {
        $facts = json_decode(file_get_contents(__DIR__ . '/Config/facts.json'), true);

        return $facts[array_rand($facts)];
    }
}