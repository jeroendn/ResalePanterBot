<?php

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Guild;
use Discord\Repository\Guild\ChannelRepository;
use Discord\WebSockets\Intents;
use jeroendn\PhpHelpers\EnvHelper;
use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/../../../../vendor/autoload.php';

EnvHelper::loadEnv(__DIR__ . '/../../../../.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

function sendFactMessage(Discord $discord, string $fact): void
{
    $embed = new Embed($discord, [
        'title'       => 'Jory\'s feitje',
        'description' => $fact
    ]);

    $message = MessageBuilder::new()->setEmbeds([$embed]);

    if (getenv('MODE') === 'PROD') {
        $guildId = getenv('RESALE_PARTNERS_GUILD_ID');
    }
    else {
        $guildId = getenv('KOTS_KAT_GUILD_ID');
    }

    $discord->guilds->fetch($guildId)->then(function (Guild $guild) use ($discord, $message) {

        $discord->getFactory()->repository(ChannelRepository::class)->fetch($guild->system_channel_id)->then(function (Channel $channel) use ($discord, $message) {
            $channel->sendMessage($message)->finally(function () {
                stop();
            });
        }, function ($e) {
            echo "Failed to fetch channel: " . $e->getMessage() . PHP_EOL;
            stop();
        });

    }, function ($e) {
        echo "Failed to fetch guild: " . $e->getMessage() . PHP_EOL;
        stop();
    });
}

function sendRandomFactMessage(Discord $discord): void
{
    $facts = json_decode(file_get_contents(__DIR__ . '/../Config/facts.json'), true);
    $fact  = $facts[array_rand($facts)];

    sendFactMessage($discord, $fact);
}

#[NoReturn] function stop(): void
{
    echo "Stopping job! \n";
    die;
}

$now = new DateTimeImmutable;

$weekDay         = date('w', $now->getTimestamp());
$shouldSendToday = !($weekDay != 6 && $weekDay != 7); // ($weekDay == 1 || $weekDay == 3 || $weekDay == 5); // Send on monday/wednesday/friday

$minTime = (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(9, 00);
$maxTime = (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(16, 45);

if (
    $shouldSendToday
    &&
    ($now->getTimestamp() > $minTime->getTimestamp() && $now->getTimestamp() < $maxTime->getTimestamp())
) {
    if (rand(1, 100) === 1) {
        sendRandomFactMessage($discord);
    }
}