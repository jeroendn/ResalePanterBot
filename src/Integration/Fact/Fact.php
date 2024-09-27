<?php

namespace ResalePanterBot\Integration\Fact;

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;
use ResalePanterBot\CreateCommand;
use ResalePanterBot\Integration\IntegrationInterface;
use ResalePanterBot\Integration\SubProcessInterface;

class Fact implements SubProcessInterface, IntegrationInterface
{
    public const COMMAND_NAME_CAT_FACT = 'cat-fact';
    public const COMMAND_INFO_CAT_FACT = 'Get a random cat fact';

    private Discord $discord;

    /**
     * @param Discord $discord
     */
    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    /**
     * @return void
     */
    public function startSubProcess(): void
    {
        $exec = sprintf('%s /dev/null 2>&1 &', '/usr/local/bin/php /var/www/html/src/Integration/Fact/Command/timer.php');

        echo "Starting: $exec \n";
        proc_open($exec, [], $pipes);
    }

    public function registerCommands(): void
    {
        CreateCommand::create($this->discord, self::COMMAND_NAME_CAT_FACT, self::COMMAND_INFO_CAT_FACT);
    }

    public function handleCatFactCommand(Interaction $interaction): void
    {
        $json = @file_get_contents('https://cat-fact.herokuapp.com/facts/random');
        $array = json_decode($json, true);

        $embed = new Embed($this->discord, [
            'title'       => 'Random cat fact',
            'description' => $array['text'] ?? 'Oops! Fact not found.'
        ]);

        $message = MessageBuilder::new()->setEmbeds([$embed]);

        $interaction->respondWithMessage($message);
    }
}