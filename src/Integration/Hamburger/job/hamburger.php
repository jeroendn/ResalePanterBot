<?php

use Discord\Discord;
use Discord\WebSockets\Intents;
use jeroendn\PhpHelpers\EnvHelper;
use ResalePanterBot\Integration\Hamburger\Hamburger;

require_once __DIR__ . '/../../../../vendor/autoload.php';

EnvHelper::loadEnv(__DIR__ . '/../../../../.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

$discord->on('init', function (Discord $discord) {

    $hamburger = new Hamburger($discord);
    $hamburger->sendHamburgerMessage()
        ->finally(function () use ($hamburger) {
            $hamburger->updatePresence();
        });

});

$discord->run();