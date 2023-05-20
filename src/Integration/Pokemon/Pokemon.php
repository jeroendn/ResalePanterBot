<?php

namespace ResalePanterBot\Integration\Pokemon;

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Option;
use Discord\Parts\Interactions\Interaction;
use Exception;
use ResalePanterBot\CreateCommand;
use ResalePanterBot\Integration\CommandInterface;
use ResalePanterBot\Integration\IntegrationInterface;

class Pokemon implements CommandInterface, IntegrationInterface
{
    public const COMMAND_NAME_FUSE        = 'fuse';
    public const COMMAND_NAME_FUSE_RANDOM = 'fuse-random';
    public const COMMAND_INFO_FUSE        = 'Fuse 2 pokemon of your choice';
    public const COMMAND_INFO_FUSE_RANDOM = 'Fuse 2 random pokemon';

    private Discord $discord;
    private array   $pokemonIds = [];

    /**
     * @param Discord $discord
     */
    public function __construct(Discord $discord)
    {
        $this->discord = $discord;

        $this->pokemonIds = json_decode(file_get_contents(__DIR__ . '/Config/pokemon.json'), true);
    }

    /**
     * @return string
     */
    public function getHelpString(): string
    {
        return '/' . self::COMMAND_NAME_FUSE . "\n" . self::COMMAND_INFO_FUSE .
            "\n\n" .
            '/' . self::COMMAND_NAME_FUSE . "\n" . self::COMMAND_INFO_FUSE;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function registerCommands(): void
    {
        CreateCommand::create($this->discord, self::COMMAND_NAME_FUSE, self::COMMAND_INFO_FUSE, [
            new Option($this->discord, [
                'name'        => 'pokemon1',
                'description' => 'Name of the first pokemon',
                'type'        => Option::STRING
            ]),
            new Option($this->discord, [
                'name'        => 'pokemon2',
                'description' => 'Name of the second pokemon',
                'type'        => Option::STRING
            ])
        ]);

        CreateCommand::create($this->discord, self::COMMAND_NAME_FUSE_RANDOM, self::COMMAND_INFO_FUSE_RANDOM);
    }

    /**
     * @param Interaction $interaction
     * @param string      $pokemonName1
     * @param string      $pokemonName2
     * @return void
     */
    public function handleFuseCommand(Interaction $interaction, string $pokemonName1, string $pokemonName2): void
    {
        $ids = array_change_key_case($this->pokemonIds);

        $pokemonId1 = $ids[strtolower($pokemonName1)];
        $pokemonId2 = $ids[strtolower($pokemonName2)];

        $imgUrl1 = "https://raw.githubusercontent.com/Aegide/custom-fusion-sprites/main/CustomBattlers/$pokemonId1.$pokemonId2.png";
        $imgUrl2 = "https://raw.githubusercontent.com/Aegide/custom-fusion-sprites/main/CustomBattlers/$pokemonId2.$pokemonId1.png";

        $result1 = @file_get_contents($imgUrl1);
        $result2 = @file_get_contents($imgUrl2);

        $messageBuilder = MessageBuilder::new();

        if ($result1 && $result2) {
            $message = $messageBuilder->setContent("$pokemonName1/$pokemonName2\n$imgUrl1\n$imgUrl2");
            $interaction->respondWithMessage($message);
        }
        elseif ($result1) {
            $message = $messageBuilder->setContent("$pokemonName1/$pokemonName2 $imgUrl1");
            $interaction->respondWithMessage($message);
        }
        elseif ($result2) {
            $message = $messageBuilder->setContent("$pokemonName2/$pokemonName1 $imgUrl2");
            $interaction->respondWithMessage($message);
        }
        else {
            respondWithError($interaction, 'No sprites available. Please try another combination.');
        }
    }

    /**
     * @param Interaction $interaction
     * @param int         $maxRetries
     * @return void
     */
    function handleRandomFuseCommand(Interaction $interaction, int $maxRetries = 0): void
    {
        $pokemonName1 = array_rand($this->pokemonIds);
        $pokemonName2 = array_rand($this->pokemonIds);

        $pokemonId1 = $this->pokemonIds[$pokemonName1];
        $pokemonId2 = $this->pokemonIds[$pokemonName2];

        $imgUrl1 = "https://raw.githubusercontent.com/Aegide/custom-fusion-sprites/main/CustomBattlers/$pokemonId1.$pokemonId2.png";
        $imgUrl2 = "https://raw.githubusercontent.com/Aegide/custom-fusion-sprites/main/CustomBattlers/$pokemonId2.$pokemonId1.png";

        $result1 = @file_get_contents($imgUrl1);
        $result2 = @file_get_contents($imgUrl2);

        $messageBuilder = MessageBuilder::new();

        if ($result1 && $result2) {
            $message = $messageBuilder->setContent("$pokemonName1/$pokemonName2\n$imgUrl1\n$imgUrl2");
            $interaction->respondWithMessage($message);
        }
        elseif ($result1) {
            $message = $messageBuilder->setContent("$pokemonName1/$pokemonName2 $imgUrl1");
            $interaction->respondWithMessage($message);
        }
        elseif ($result2) {
            $message = $messageBuilder->setContent("$pokemonName2/$pokemonName1 $imgUrl2");
            $interaction->respondWithMessage($message);
        }
        elseif ($maxRetries) {
            $this->handleRandomFuseCommand($interaction, --$maxRetries);
        }
        else {
            respondWithError($interaction, 'Timeout, no sprite was found within the given limit.');
        }
    }

}