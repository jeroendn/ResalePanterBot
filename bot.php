<?php

require_once __DIR__ . '/src/lock.php';

require_once __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use jeroendn\PhpHelpers\EnvHelper;

EnvHelper::loadEnv(__DIR__ . '/.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

require_once __DIR__ . '/src/Integration/PingPong/pingpong.php';
require_once __DIR__ . '/src/Integration/Hamburger/hamburger.php';
require_once __DIR__ . '/src/Integration/Pokemon/pokemon.php';

$discord->on('ready', function (Discord $discord) {

    echo "\033[38;2;78;50;122m
                                                           /(((/                
                                                            ./((/               
                                                              (///              
                                                               //(/             
                                                               ,//(,            
          (/.            */(((                                 ,(///            
          ,(((/(////(/((((//.                                  //((/            
            ((/(O((((O(((((,                                 //////             
             ///(((/((/((((//          (((/(/(/(//(///((/(/(///((.              
              /(//.(((/(((///(/(/((/(//((/(/(/(/(/((/((/(((/(((*                
                (//(/((/((((((/////////(//(/(/(//((////(/((((/,                 
                  *((((///(//(/(/(((////(/(////((///(/(/(/(/(/                  
                   /(((/((/(((//(////(///(/(((((((/(((/((((.                    
                   ////(/(/(((/(//(//(((//((/(///((/(//((                       
                   //(/(//((/(//(/((/          */(/((/(/((/                     
                /(((////  //((/                 //(/((*(((/(/                   
               /((/(((//////(/              /(/(/(////(/(((/(                   \033[0m", PHP_EOL;


    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        echo $message;
        if (strtolower($message->content) === getenv('COMMAND_CHAR') . 'help') {
            $message->reply("!fuse {pokemon1} {pokemon2}
Fuse 2 Pokemon

!fuse
Fuse 2 random Pokemon

!ping
Check bot status");
        }
    });

});

$discord->run();