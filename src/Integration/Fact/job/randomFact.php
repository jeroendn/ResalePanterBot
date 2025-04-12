<?php

use Discord\Discord;
use Discord\WebSockets\Intents;
use jeroendn\PhpHelpers\EnvHelper;
use ResalePanterBot\Integration\Fact\Fact;

require_once __DIR__ . '/../../../../vendor/autoload.php';

EnvHelper::loadEnv(__DIR__ . '/../../../../.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

$discord->on('ready', function (Discord $discord) {

    if (rand(1, 100) === 1) {
        $fact = new Fact($discord);
        $fact->sendFactMessage();
    }

});