<?php

use Discord\Discord;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Discord\Repository\Guild\ChannelRepository;
use Discord\WebSockets\Intents;
use jeroendn\PhpHelpers\EnvHelper;
use ResalePanterBot\Integration\Fact\Fact;

require_once __DIR__ . '/../../../../vendor/autoload.php';

EnvHelper::loadEnv(__DIR__ . '/../../../../.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

$discord->on('init', function (Discord $discord) {

    if (getenv('MODE') === 'PROD') {
        $guildId = getenv('RESALE_PARTNERS_GUILD_ID');
    }
    else {
        $guildId = getenv('KOTS_KAT_GUILD_ID');
    }

    $discord->guilds->fetch($guildId)
        ->then(
            function (Guild $guild) use ($discord) {

                $discord->getFactory()->repository(ChannelRepository::class)->fetch($guild->system_channel_id)
                    ->then(
                        function (Channel $channel) use ($discord) {

                            $channel->getMessageHistory(['limit' => 1])
                                ->then(
                                    function (Collection $messages) use ($discord) {

                                        $lastMessageWasFromBot = false;

                                        /** @var Message $message */
                                        foreach ($messages as $message) {
                                            $lastMessageWasFromBot = $message->user_id === $discord->user->id;
                                            break;
                                        }

                                        if ($lastMessageWasFromBot) {
                                            echo 'Not sending message, because previous message also from the bot itself' . PHP_EOL;
                                            $discord->close(); // Stop execution when not doing anything as well.
                                        }

                                        if (!$lastMessageWasFromBot /* && rand(1, 100) === 1 */) {
                                            $fact = new Fact($discord);
                                            $fact->sendFactMessage();
                                        }
                                        else {
                                            echo 'Not sending message, because luck was not in our favor' . PHP_EOL;
                                            $discord->close(); // Stop execution when not doing anything as well.
                                        }

                                    },
                                    function ($e) use ($discord) {
                                        echo "Failed to fetch message history: " . $e->getMessage() . PHP_EOL;
                                        $discord->close();
                                    }
                                );

                        },
                        function ($e) use ($discord) {
                            echo "Failed to fetch channel: " . $e->getMessage() . PHP_EOL;
                            $discord->close();
                        }
                    );

            },
            function ($e) use ($discord) {
                echo "Failed to fetch guild: " . $e->getMessage() . PHP_EOL;
                $discord->close();
            }
        );

});
