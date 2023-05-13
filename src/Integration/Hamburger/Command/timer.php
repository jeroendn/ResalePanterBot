<?php

use Discord\Discord;
use Discord\Http\Exceptions\NoPermissionsException;
use Discord\Parts\User\Activity;
use Discord\WebSockets\Intents;
use jeroendn\PhpHelpers\EnvHelper;
use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;

require_once __DIR__ . '/../../../../vendor/autoload.php';

EnvHelper::loadEnv(__DIR__ . '/../../../../.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

enableTimer($discord);

/**
 * @param Discord $discord
 * @return void
 */
function enableTimer(Discord $discord): void
{
    $sendNotifications = [];

    $interval = 200;

    $loop = Loop::get();

    // Schedule the time check to run every $interval seconds
    $loop->addPeriodicTimer($interval, function (TimerInterface $timer) use (&$loop, &$discord, &$sendNotifications) {

        $now = new DateTimeImmutable;

        echo 'Hamburger timer is at: ' . $now->format('Y/m/d H:i:s') . PHP_EOL;

        updatePresence($discord, $now);

        sendHamburgerMessage($discord, $now, $sendNotifications);

    });

    $loop->run();

    exit('Hamburger timer has stopped');
}

/**
 * @param Discord           $discord
 * @param DateTimeImmutable $now
 * @return void
 */
function updatePresence(Discord $discord, DateTimeImmutable $now): void
{
    $weekDay  = date('w', $now->getTimestamp());
    $isFriday = ($weekDay == 5);

    $start          = (new DateTimeImmutable('now'))->setTime(11, 45);
    $end            = (new DateTimeImmutable('now'))->setTime(12, 30);
    $dontClearAfter = $end->modify('+15 minutes');

    if ($isFriday && $now->getTimestamp() > $start->getTimestamp() && $now->getTimestamp() < $end->getTimestamp()) {
        $activity = new Activity($discord, ['name' => '🍔 Hamburgers!', 'type' => Activity::TYPE_PLAYING]);

        $discord->updatePresence($activity);
    }
    elseif ($isFriday && $now->getTimestamp() > $end->getTimestamp() && $now->getTimestamp() < $dontClearAfter->getTimestamp()) {
        $discord->updatePresence();
    }
}

/**
 * @param Discord           $discord
 * @param DateTimeImmutable $now
 * @param array             $sendNotifications
 * @return void
 * @throws NoPermissionsException
 */
function sendHamburgerMessage(Discord $discord, DateTimeImmutable $now, array &$sendNotifications): void
{
    $start = (new DateTimeImmutable('now'))->setTime(11, 45);
    $end   = (new DateTimeImmutable('now'))->setTime(12, 00);

    $arrayKey = $now->format('Ymd');

    if (
        $now->getTimestamp() > $start->getTimestamp() && $now->getTimestamp() < $end->getTimestamp()
        &&
        !array_key_exists($arrayKey, $sendNotifications)
    ) {
        $guild           = $discord->guilds[getenv('RESALE_PARTNERS_GUILD_ID')];
        $systemChannelId = $guild['system_channel_id'];

        $channel = $discord->getChannel($systemChannelId);

        $channel->sendMessage("🍔🍔🍔HAMBURGERS🍔🍔🍔\nhttps://i.giphy.com/media/dGyzYOvRPn21y/giphy.webp");

        $sendNotifications[$arrayKey] = true;
    }
}