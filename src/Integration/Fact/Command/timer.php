<?php

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Http\Exceptions\NoPermissionsException;
use Discord\Parts\Embed\Embed;
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
    $facts = json_decode(file_get_contents(__DIR__ . '/../Config/facts.json'), true);

    $lastFactSendOn = new DateTimeImmutable('yesterday'); // TODO Keep send history persistent
    $sendFactOn     = createRandomTime();

    $interval = 500;

    $loop = Loop::get();

    // Schedule the time check to run every $interval seconds
    $loop->addPeriodicTimer($interval, function (TimerInterface $timer) use (&$loop, &$discord, &$facts, $sendFactOn, &$lastFactSendOn) {

        $fact = $facts[array_rand($facts)];

        $force = factHasBeenSendBeforeToday($lastFactSendOn);

        if (!factHasBeenSendBeforeNow($lastFactSendOn) || $force) {
            sendFactMessage($discord, $fact, $sendFactOn, $lastFactSendOn, $force);
        }
        else {
            // TODO kill
        }

    });

    $loop->run();

    exit('Fact timer has stopped');
}

/**
 * @param Discord           $discord
 * @param string            $fact
 * @param DateTimeImmutable $sendFactOn
 * @param DateTimeImmutable $lastFactSendOn
 * @param bool              $force Force sending message without checking time
 * @return void
 * @throws NoPermissionsException
 */
function sendFactMessage(Discord $discord, string $fact, DateTimeImmutable $sendFactOn, DateTimeImmutable &$lastFactSendOn, bool $force = false): void
{
    $now = new DateTimeImmutable;

    $weekDay         = date('w', $now->getTimestamp());
    $shouldSendToday = ($weekDay == 1 || $weekDay == 3 || $weekDay == 5); // Send on monday/wednesday/friday

    $start = $sendFactOn;
    $end   = $sendFactOn->modify('+30 minutes');

    if (
        $shouldSendToday
        &&
        ($now->getTimestamp() > $start->getTimestamp() && $now->getTimestamp() < $end->getTimestamp() || $force)
    ) {
        $embed = new Embed($discord, [
            'title'       => 'Jory\'s feitje',
            'description' => $fact
        ]);

        $message = MessageBuilder::new()->setEmbeds([$embed]);

        $guild           = $discord->guilds[getenv('RESALE_PARTNERS_GUILD_ID')];
        $systemChannelId = $guild['system_channel_id'];

        $channel = $discord->getChannel($systemChannelId);

        $channel->sendMessage($message);

        $lastFactSendOn = $now;
    }
}

/**
 * @return DateTimeImmutable
 */
function createRandomTime(): DateTimeImmutable
{
    $start = (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(9, 00);
    $end   = (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(16, 45);

    $startTimestamp = $start->getTimestamp();
    $endTimestamp   = $end->getTimestamp();

    $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);

    return (new DateTimeImmutable())->setTimestamp($randomTimestamp);
}

/**
 * @param DateTimeImmutable $lastFactSendOn
 * @return bool
 */
function factHasBeenSendBeforeNow(DateTimeImmutable $lastFactSendOn): bool
{
    return $lastFactSendOn->getTimestamp() < (new DateTimeImmutable('now'))->getTimestamp();
}

/**
 * @param DateTimeImmutable $lastFactSendOn
 * @return bool
 */
function factHasBeenSendBeforeToday(DateTimeImmutable $lastFactSendOn): bool
{
    return $lastFactSendOn->getTimestamp() < (new DateTimeImmutable('now'))->setTimezone(new DateTimeZone('Europe/Amsterdam'))->setTime(0, 0)->getTimestamp();
}
