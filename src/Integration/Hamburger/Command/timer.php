<?php

use Discord\Discord;
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

// This is the child process
// Define the interval (in seconds) between each time check
$interval = 120;

// Create a new event loop instance
$loop = Loop::get();

// Schedule the time check to run every $interval seconds
$loop->addPeriodicTimer($interval, function (TimerInterface $timer) use (&$loop, &$discord) {

    $now = new DateTimeImmutable;

    echo 'Hamburger timer is at: ' . $now->format('Y/m/d H:i:s') . PHP_EOL;

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

});

$loop->run();

// Exit the child process
exit('Hamburger timer has stopped');