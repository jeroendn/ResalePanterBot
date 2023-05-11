<?php
#!/usr/local/bin/php
include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use jeroendn\PhpHelpers\EnvHelper;

EnvHelper::loadEnv(__DIR__ . '/.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

require_once __DIR__ . '/src/pkmn.php';

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        if ($message->content === 'ping') {
            $message->reply('pong');
        }
    });
});

$discord->run();