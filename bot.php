<?php

require_once __DIR__ . '/src/lock.php';

require_once __DIR__ . '/vendor/autoload.php';

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\InteractionType;
use Discord\Parts\Interactions\Interaction;
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

    $discord->application->commands->save(
        $discord->application->commands->create(CommandBuilder::new()
            ->setName('help')
            ->setDescription('Get a list of available commands')
            ->toArray()
        )
    );

    $discord->application->commands->save(
        $discord->application->commands->create(CommandBuilder::new()
            ->setName('ping')
            ->setDescription('Check bot online status')
            ->toArray()
        )
    );

//    $discord->application->commands->save(
//        $discord->application->commands->create(CommandBuilder::new()
//            ->setName('fuse')
//            ->setDescription('Fuse 2 Pokemon')
//            ->toArray()
//        )
//    );

//    $discord->application->commands->save(
//        $discord->application->commands->create(CommandBuilder::new()
//            ->setName('fuserandom')
//            ->setDescription('Fuse 2 random Pokemon')
//            ->toArray()
//        )
//    );

    $discord->on(Event::INTERACTION_CREATE, function (Interaction $interaction, Discord $discord) {
        if ($interaction->type === InteractionType::APPLICATION_COMMAND) {

            switch ($interaction->data->name) {
                case 'help':
                    $message = MessageBuilder::new()->setContent("!fuse {pokemon1} {pokemon2}
Fuse 2 Pokemon (Not available as slash command yet)

!fuse
Fuse 2 random Pokemon (Not available as slash command yet)

/ping
Check bot status");

                    try {
                        $interaction->respondWithMessage($message);
                    }
                    catch (Throwable $e) {
                        echo $e->getMessage();
                    }

                    break;
                case 'ping':
                    $message = MessageBuilder::new()->setContent('ping');

                    try {
                        $interaction->respondWithMessage($message);
                    }
                    catch (Throwable $e) {
                        echo $e->getMessage();
                    }

                    break;
                case 'fuse':
                    // TODO

                    break;
                case 'fuserandom':
                    // TODO

                    break;
            }

        }
    });

});

$discord->run();