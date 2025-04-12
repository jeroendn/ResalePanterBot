<?php

namespace ResalePanterBot\Integration\Hamburger;

use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Activity;
use Discord\Repository\Guild\ChannelRepository;
use Exception;
use React\Promise\PromiseInterface;
use ResalePanterBot\Integration\IntegrationInterface;

final class Hamburger implements IntegrationInterface
{
    private const array HAMBURGER_IMAGES = [
        'https://kappa.jeroendn.nl/WUNi7/ZAwekarE72.png',
        'https://c.tenor.com/_w2En8QGqe4AAAAC/tenor.gif',
        'https://c.tenor.com/lNhF82M3IfAAAAAd/tenor.gif',
        'https://c.tenor.com/84vbqWFxLuYAAAAC/tenor.gif',
        'https://c.tenor.com/DJt8eN7_KdkAAAAd/tenor.gif'
    ];

    public function __construct(
        private readonly Discord $discord
    ) {}

    public function updatePresence(): void
    {
        $activity = new Activity($this->discord, ['name' => '🍔 Hamburgers!', 'type' => Activity::TYPE_GAME]);

        $this->discord->updatePresence($activity);

        // Keep status for X minutes
        $this->discord->getLoop()->addTimer(60 * 45, function () {
            $this->discord->updatePresence();

            echo "Exiting...\n";
            $this->discord->close(); // Clean shutdown
        });
    }

    /**
     * @throws Exception
     */
    public function sendHamburgerMessage(): PromiseInterface
    {
        if (getenv('MODE') === 'PROD') {
            $guildId = getenv('RESALE_PARTNERS_GUILD_ID');
        }
        else {
            $guildId = getenv('KOTS_KAT_GUILD_ID');
        }

        return $this->discord->guilds->fetch($guildId)
            ->then(
                function (Guild $guild) {

                    return $this->discord->getFactory()->repository(ChannelRepository::class)->fetch($guild->system_channel_id)
                        ->then(
                            function (Channel $channel) {

                                return $channel->sendMessage('🍔🍔🍔 HAMBURGERS 🍔🍔🍔')
                                    ->finally(function () use ($channel) {
                                        $imageUrl = self::HAMBURGER_IMAGES[rand(0, count(self::HAMBURGER_IMAGES) - 1)];
                                        return $channel->sendMessage($imageUrl);
                                    });
                            },
                            function ($e) {
                                echo "Failed to fetch channel: " . $e->getMessage() . PHP_EOL;
                            }
                        );

                },
                function ($e) {
                    echo "Failed to fetch guild: " . $e->getMessage() . PHP_EOL;
                }
            );
    }
}