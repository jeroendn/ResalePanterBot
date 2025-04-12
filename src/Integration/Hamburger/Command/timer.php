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

const HAMBURGER_IMAGES = [
    'https://kappa.jeroendn.nl/WUNi7/ZAwekarE72.png',
    'https://media1.tenor.com/m/_w2En8QGqe4AAAAC/burger-hungry.gif',
    'https://media1.tenor.com/m/lNhF82M3IfAAAAAd/cheeseburger-burger.gif',
    'https://media1.tenor.com/m/84vbqWFxLuYAAAAC/burgers-patrick-star.gif',
    'https://media1.tenor.com/m/DJt8eN7_KdkAAAAd/hey-kitty-you-can-have-cheeze-burger-cheeze-burger.gif'
];

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

    $start          = (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(11, 45);
    $end            = (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(12, 30);
    $dontClearAfter = $end->modify('+15 minutes');

    if (
        $isFriday
        &&
        $now->getTimestamp() > $start->getTimestamp() && $now->getTimestamp() < $end->getTimestamp()
    ) {
        $activity = new Activity($discord, ['name' => '🍔 Hamburgers!', 'type' => Activity::TYPE_GAME]);

        $discord->updatePresence($activity);
    }
    elseif (
        $isFriday
        &&
        $now->getTimestamp() > $end->getTimestamp() && $now->getTimestamp() < $dontClearAfter->getTimestamp()
    ) {
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
    $weekDay  = date('w', $now->getTimestamp());
    $isFriday = ($weekDay == 5);

    $start = (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(11, 45);
    $end   = (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(12, 00);

    $arrayKey = $now->format('Ymd');

    if (
        $isFriday
        &&
        $now->getTimestamp() > $start->getTimestamp() && $now->getTimestamp() < $end->getTimestamp()
        &&
        !array_key_exists($arrayKey, $sendNotifications)
    ) {
        $guild           = $discord->guilds[getenv('RESALE_PARTNERS_GUILD_ID')];
        $systemChannelId = $guild['system_channel_id'];

        $channel = $discord->getChannel($systemChannelId);

        $imageUrl = HAMBURGER_IMAGES[rand(0, count(HAMBURGER_IMAGES))];

        $channel->sendMessage('🍔🍔🍔 HAMBURGERS 🍔🍔🍔');
        $channel->sendMessage($imageUrl);

        $sendNotifications[$arrayKey] = true;
    }
}