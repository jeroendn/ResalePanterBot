<?php
/**
 * @var Discord $discord
 */

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

//$discord->on('ready', function (Discord $discord) {
//
//    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
//        if (strtolower($message->content) === getenv('COMMAND_CHAR') . 'ping') {
//            $message->reply('pong');
//        }
//    });
//
//});