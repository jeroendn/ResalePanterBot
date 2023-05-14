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
use ResalePanterBot\Integration\Hamburger\Hamburger;
use ResalePanterBot\Integration\PingPong\PingPong;
use ResalePanterBot\Integration\Pokemon\Pokemon;

EnvHelper::loadEnv(__DIR__ . '/.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

require_once __DIR__ . '/src/functions.php';

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

    $pingPong  = new PingPong($discord);
    $pokemon   = new Pokemon($discord);
    $hamburger = new Hamburger($discord);

    $discord->application->commands->clear(); // Clear registered commands in case of change or removal of command

    $discord->application->commands->save(
        $discord->application->commands->create(CommandBuilder::new()
            ->setName('help')
            ->setDescription('Get a list of available commands')
            ->toArray()
        )
    );
    $pingPong->registerCommands();
    $pokemon->registerCommands();

    $hamburger->startTimerProcess();

    echo "\033[0m\033[48;2;0;128;0m READY FOR EVENT LISTENING \033[0m", PHP_EOL;

    $discord->on(Event::INTERACTION_CREATE, function (Interaction $interaction, Discord $discord) use ($pingPong, $pokemon) {
        if ($interaction->type === InteractionType::APPLICATION_COMMAND) {

            switch ($interaction->data->name) {
                case 'help':
                    $message = MessageBuilder::new()->setContent($pokemon->getHelpString() . "\n\n" . $pingPong->getHelpString());

                    try {
                        $interaction->respondWithMessage($message);
                    }
                    catch (Throwable $e) {
                        respondWithError($interaction, $e->getMessage());
                        echo $e->getMessage();
                    }

                    break;
                case PingPong::COMMAND_NAME:
                    $pingPong->handleCommand($interaction);
                    break;
                case Pokemon::COMMAND_NAME_FUSE:
                    $options = $interaction->data->options->toArray();

                    $pokemon->handleFuseCommand($interaction, $options['pokemon1']['value'], $options['pokemon2']['value']);
                    break;
                case Pokemon::COMMAND_NAME_FUSE_RANDOM:
                    $pokemon->handleRandomFuseCommand($interaction, 5);
                    break;
                default:
                    respondWithError($interaction, 'Command handler not found.');
                    break;
            }

        }
    });

});

$discord->run();