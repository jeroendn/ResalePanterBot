<?php

require_once __DIR__ . '/src/lock.php';

require_once __DIR__ . '/vendor/autoload.php';

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\InteractionType;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use jeroendn\PhpHelpers\EnvHelper;
use ResalePanterBot\CreateCommand;
use ResalePanterBot\DeleteCommands;
use ResalePanterBot\Integration\Fact\Fact;
use ResalePanterBot\Integration\PingPong\PingPong;
use ResalePanterBot\Integration\Pokemon\Pokemon;

EnvHelper::loadEnv(__DIR__ . '/.env');

$discord = new Discord([
    'token'   => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents()
]);

require_once __DIR__ . '/src/functions.php';

$discord->on('init', function (Discord $discord) {

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
    $fact      = new Fact($discord);

    refreshCommands($discord, $pingPong, $pokemon, $fact); // This has to be executed only once

    echo "\033[0m\033[48;2;0;128;0m READY FOR EVENT LISTENING \033[0m", PHP_EOL;

    $discord->on(Event::INTERACTION_CREATE, function (Interaction $interaction, Discord $discord) use ($pingPong, $pokemon, $fact) {
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
                case Pokemon::COMMAND_NAME_FUSE_OPTIONS:
                    $pokemon->handleFuseOptionsCommand($interaction);
                    break;
                case Pokemon::COMMAND_NAME_FUSE:
                    $options = $interaction->data->options->toArray();

                    if (!isset($options['pokemon1']['value'], $options['pokemon2']['value'])) {
                        respondWithError($interaction, 'Please provide both options.');
                        break;
                    }

                    $pokemon->handleFuseCommand($interaction, $options['pokemon1']['value'], $options['pokemon2']['value']);
                    break;
                case Pokemon::COMMAND_NAME_FUSE_RANDOM:
                    $pokemon->handleRandomFuseCommand($interaction, 5);
                    break;
                case Fact::COMMAND_NAME_RANDOM_FACT:
                    $fact->handleRandomFactCommand($interaction);
                    break;
                default:
                    respondWithError($interaction, 'Command handler not found.');
                    break;
            }

        }
    });

});

$discord->run();

/**
 * @param Discord  $discord
 * @param PingPong $pingPong
 * @param Pokemon  $pokemon
 * @param Fact     $fact
 * @return void
 * @throws Exception
 */
function refreshCommands(Discord $discord, PingPong $pingPong, Pokemon $pokemon, Fact $fact): void
{
    DeleteCommands::delete($discord)->then(function () use ($discord, $pingPong, $pokemon, $fact) {
        CreateCommand::create($discord, 'help', 'Get a list of available commands');

        $pingPong->registerCommands();
        $pokemon->registerCommands();
        $fact->registerCommands();
    });
}