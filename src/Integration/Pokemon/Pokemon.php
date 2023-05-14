<?php

namespace ResalePanterBot\Integration\Pokemon;

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Interactions\Command\Option;
use Discord\Parts\Interactions\Interaction;
use Exception;
use ResalePanterBot\Integration\CommandInterface;
use ResalePanterBot\Integration\IntegrationInterface;

class Pokemon implements CommandInterface, IntegrationInterface
{
    public const COMMAND_NAME_FUSE        = 'fuse';
    public const COMMAND_NAME_FUSE_RANDOM = 'fuse-random';
    public const COMMAND_INFO_FUSE        = 'Fuse 2 pokemon of your choice';
    public const COMMAND_INFO_FUSE_RANDOM = 'Fuse 2 random pokemon';

    private const IDS = [
        'Bulbasaur'         => 1,
        'Ivysaur'           => 2,
        'Venusaur'          => 3,
        'Charmander'        => 4,
        'Charmeleon'        => 5,
        'Charizard'         => 6,
        'Squirtle'          => 7,
        'Wartortle'         => 8,
        'Blastoise'         => 9,
        'Caterpie'          => 10,
        'Metapod'           => 11,
        'Butterfree'        => 12,
        'Weedle'            => 13,
        'Kakuna'            => 14,
        'Beedrill'          => 15,
        'Pidgey'            => 16,
        'Pidgeotto'         => 17,
        'Pidgeot'           => 18,
        'Rattata'           => 19,
        'Raticate'          => 20,
        'Spearow'           => 21,
        'Fearow'            => 22,
        'Ekans'             => 23,
        'Arbok'             => 24,
        'Pikachu'           => 25,
        'Raichu'            => 26,
        'Sandshrew'         => 27,
        'Sandslash'         => 28,
        'Nidoran-f'         => 29,
        'Nidorina'          => 30,
        'Nidoqueen'         => 31,
        'Nidoran-m'         => 32,
        'Nidorino'          => 33,
        'Nidoking'          => 34,
        'Clefairy'          => 35,
        'Clefable'          => 36,
        'Vulpix'            => 37,
        'Ninetales'         => 38,
        'Jigglypuff'        => 39,
        'Wigglytuff'        => 40,
        'Zubat'             => 41,
        'Golbat'            => 42,
        'Oddish'            => 43,
        'Gloom'             => 44,
        'Vileplume'         => 45,
        'Paras'             => 46,
        'Parasect'          => 47,
        'Venonat'           => 48,
        'Venomoth'          => 49,
        'Diglett'           => 50,
        'Dugtrio'           => 51,
        'Meowth'            => 52,
        'Persian'           => 53,
        'Psyduck'           => 54,
        'Golduck'           => 55,
        'Mankey'            => 56,
        'Primeape'          => 57,
        'Growlithe'         => 58,
        'Arcanine'          => 59,
        'Poliwag'           => 60,
        'Poliwhirl'         => 61,
        'Poliwrath'         => 62,
        'Abra'              => 63,
        'Kadabra'           => 64,
        'Alakazam'          => 65,
        'Machop'            => 66,
        'Machoke'           => 67,
        'Machamp'           => 68,
        'Bellsprout'        => 69,
        'Weepinbell'        => 70,
        'Victreebel'        => 71,
        'Tentacool'         => 72,
        'Tentacruel'        => 73,
        'Geodude'           => 74,
        'Graveler'          => 75,
        'Golem'             => 76,
        'Ponyta'            => 77,
        'Rapidash'          => 78,
        'Slowpoke'          => 79,
        'Slowbro'           => 80,
        'Magnemite'         => 81,
        'Magneton'          => 82,
        'Farfetchd'         => 83,
        'Doduo'             => 84,
        'Dodrio'            => 85,
        'Seel'              => 86,
        'Dewgong'           => 87,
        'Grimer'            => 88,
        'Muk'               => 89,
        'Shellder'          => 90,
        'Cloyster'          => 91,
        'Gastly'            => 92,
        'Haunter'           => 93,
        'Gengar'            => 94,
        'Onix'              => 95,
        'Drowzee'           => 96,
        'Hypno'             => 97,
        'Krabby'            => 98,
        'Kingler'           => 99,
        'Voltorb'           => 100,
        'Electrode'         => 101,
        'Exeggcute'         => 102,
        'Exeggutor'         => 103,
        'Cubone'            => 104,
        'Marowak'           => 105,
        'Hitmonlee'         => 106,
        'Hitmonchan'        => 107,
        'Lickitung'         => 108,
        'Koffing'           => 109,
        'Weezing'           => 110,
        'Rhyhorn'           => 111,
        'Rhydon'            => 112,
        'Chansey'           => 113,
        'Tangela'           => 114,
        'Kangaskhan'        => 115,
        'Horsea'            => 116,
        'Seadra'            => 117,
        'Goldeen'           => 118,
        'Seaking'           => 119,
        'Staryu'            => 120,
        'Starmie'           => 121,
        'Mr-Mime'           => 122,
        'Scyther'           => 123,
        'Jynx'              => 124,
        'Electabuzz'        => 125,
        'Magmar'            => 126,
        'Pinsir'            => 127,
        'Tauros'            => 128,
        'Magikarp'          => 129,
        'Gyarados'          => 130,
        'Lapras'            => 131,
        'Ditto'             => 132,
        'Eevee'             => 133,
        'Vaporeon'          => 134,
        'Jolteon'           => 135,
        'Flareon'           => 136,
        'Porygon'           => 137,
        'Omanyte'           => 138,
        'Omastar'           => 139,
        'Kabuto'            => 140,
        'Kabutops'          => 141,
        'Aerodactyl'        => 142,
        'Snorlax'           => 143,
        'Articuno'          => 144,
        'Zapdos'            => 145,
        'Moltres'           => 146,
        'Dratini'           => 147,
        'Dragonair'         => 148,
        'Dragonite'         => 149,
        'Mewtwo'            => 150,
        'Mew'               => 151,
        'Chikorita'         => 152,
        'Bayleef'           => 153,
        'Meganium'          => 154,
        'Cyndaquil'         => 155,
        'Quilava'           => 156,
        'Typhlosion'        => 157,
        'Totodile'          => 158,
        'Croconaw'          => 159,
        'Feraligatr'        => 160,
        'Sentret'           => 161,
        'Furret'            => 162,
        'Hoothoot'          => 163,
        'Noctowl'           => 164,
        'Ledyba'            => 165,
        'Ledian'            => 166,
        'Spinarak'          => 167,
        'Ariados'           => 168,
        'Crobat'            => 169,
        'Chinchou'          => 170,
        'Lanturn'           => 171,
        'Pichu'             => 172,
        'Cleffa'            => 173,
        'Igglybuff'         => 174,
        'Togepi'            => 175,
        'Togetic'           => 176,
        'Natu'              => 177,
        'Xatu'              => 178,
        'Mareep'            => 179,
        'Flaaffy'           => 180,
        'Ampharos'          => 181,
        'Bellossom'         => 182,
        'Marill'            => 183,
        'Azumarill'         => 184,
        'Sudowoodo'         => 185,
        'Politoed'          => 186,
        'Hoppip'            => 187,
        'Skiploom'          => 188,
        'Jumpluff'          => 189,
        'Aipom'             => 190,
        'Sunkern'           => 191,
        'Sunflora'          => 192,
        'Yanma'             => 193,
        'Wooper'            => 194,
        'Quagsire'          => 195,
        'Espeon'            => 196,
        'Umbreon'           => 197,
        'Murkrow'           => 198,
        'Slowking'          => 199,
        'Misdreavus'        => 200,
        'Unown'             => 201,
        'Wobbuffet'         => 202,
        'Girafarig'         => 203,
        'Pineco'            => 204,
        'Forretress'        => 205,
        'Dunsparce'         => 206,
        'Gligar'            => 207,
        'Steelix'           => 208,
        'Snubbull'          => 209,
        'Granbull'          => 210,
        'Qwilfish'          => 211,
        'Scizor'            => 212,
        'Shuckle'           => 213,
        'Heracross'         => 214,
        'Sneasel'           => 215,
        'Teddiursa'         => 216,
        'Ursaring'          => 217,
        'Slugma'            => 218,
        'Magcargo'          => 219,
        'Swinub'            => 220,
        'Piloswine'         => 221,
        'Corsola'           => 222,
        'Remoraid'          => 223,
        'Octillery'         => 224,
        'Delibird'          => 225,
        'Mantine'           => 226,
        'Skarmory'          => 227,
        'Houndour'          => 228,
        'Houndoom'          => 229,
        'Kingdra'           => 230,
        'Phanpy'            => 231,
        'Donphan'           => 232,
        'Porygon2,'         => 233,
        'Stantler'          => 234,
        'Smeargle'          => 235,
        'Tyrogue'           => 236,
        'Hitmontop'         => 237,
        'Smoochum'          => 238,
        'Elekid'            => 239,
        'Magby'             => 240,
        'Miltank'           => 241,
        'Blissey'           => 242,
        'Raikou'            => 243,
        'Entei'             => 244,
        'Suicune'           => 245,
        'Larvitar'          => 246,
        'Pupitar'           => 247,
        'Tyranitar'         => 248,
        'Lugia'             => 249,
        'Ho-Oh'             => 250,
        'Celebi'            => 251,
        'Azurill'           => 252,
        'Wynaut'            => 253,
        'Ambipom'           => 254,
        'Mismagius'         => 255,
        'Honchkrow'         => 256,
        'Bonsly'            => 257,
        'Mime-Jr'           => 258,
        'Happiny'           => 259,
        'Munchlax'          => 260,
        'Mantyke'           => 261,
        'Weavile'           => 262,
        'Magnezone'         => 263,
        'Lickilicky'        => 264,
        'Rhyperior'         => 265,
        'Tangrowth'         => 266,
        'Electivire'        => 267,
        'Magmortar'         => 268,
        'Togekiss'          => 269,
        'Yanmega'           => 270,
        'Leafeon'           => 271,
        'Glaceon'           => 272,
        'Gliscor'           => 273,
        'Mamoswine'         => 274,
        'Porygon-Z'         => 275,
        'Treecko'           => 276,
        'Grovyle'           => 277,
        'Sceptile'          => 278,
        'Torchic'           => 279,
        'Combusken'         => 280,
        'Blaziken'          => 281,
        'Mudkip'            => 282,
        'Marshtomp'         => 283,
        'Swampert'          => 284,
        'Ralts'             => 285,
        'Kirlia'            => 286,
        'Gardevoir'         => 287,
        'Gallade'           => 288,
        'Shedinja'          => 289,
        'Kecleon'           => 290,
        'Beldum'            => 291,
        'Metang'            => 292,
        'Metagross'         => 293,
        'Bidoof'            => 294,
        'Spiritomb'         => 295,
        'Lucario'           => 296,
        'Gible'             => 297,
        'Gabite'            => 298,
        'Garchomp'          => 299,
        'Mawile'            => 300,
        'Lileep'            => 301,
        'Cradily'           => 302,
        'Anorith'           => 303,
        'Armaldo'           => 304,
        'Cranidos'          => 305,
        'Rampardos'         => 306,
        'Shieldon'          => 307,
        'Bastiodon'         => 308,
        'Slaking'           => 309,
        'Absol'             => 310,
        'Duskull'           => 311,
        'Dusclops'          => 312,
        'Dusknoir'          => 313,
        'Wailord'           => 314,
        'Arceus'            => 315,
        'Turtwig'           => 316,
        'Grotle'            => 317,
        'Torterra'          => 318,
        'Chimchar'          => 319,
        'Monferno'          => 320,
        'Infernape'         => 321,
        'Piplup'            => 322,
        'Prinplup'          => 323,
        'Empoleon'          => 324,
        'Nosepass'          => 325,
        'Probopass'         => 326,
        'Honedge'           => 327,
        'Doublade'          => 328,
        'Aegislash-shield'  => 329,
        'Pawniard'          => 330,
        'Bisharp'           => 331,
        'Luxray'            => 332,
        'Aggron'            => 333,
        'Flygon'            => 334,
        'Milotic'           => 335,
        'Salamence'         => 336,
        'Klinklang'         => 337,
        'Zoroark'           => 338,
        'Sylveon'           => 339,
        'Kyogre'            => 340,
        'Groudon'           => 341,
        'Rayquaza'          => 342,
        'Dialga'            => 343,
        'Palkia'            => 344,
        'Giratina-altered'  => 345,
        'Regigigas'         => 346,
        'Darkrai'           => 347,
        'Genesect'          => 348,
        'Reshiram'          => 349,
        'Zekrom'            => 350,
        'Kyurem'            => 351,
        'Roserade'          => 352,
        'Drifblim'          => 353,
        'Lopunny'           => 354,
        'Breloom'           => 355,
        'Ninjask'           => 356,
        'Banette'           => 357,
        'Rotom'             => 358,
        'Reuniclus'         => 359,
        'Whimsicott'        => 360,
        'Krookodile'        => 361,
        'Cofagrigus'        => 362,
        'Galvantula'        => 363,
        'Ferrothorn'        => 364,
        'Litwick'           => 365,
        'Lampent'           => 366,
        'Chandelure'        => 367,
        'Haxorus'           => 368,
        'Golurk'            => 369,
        'Pyukumuku'         => 370,
        'Klefki'            => 371,
        'Talonflame'        => 372,
        'Mimikyu-disguised' => 373,
        'Volcarona'         => 374,
        'Deino'             => 375,
        'Zweilous'          => 376,
        'Hydreigon'         => 377,
        'Latias'            => 378,
        'Latios'            => 379,
        'Deoxys-normal'     => 380,
        'Jirachi'           => 381,
        'Nincada'           => 382,
        'Bibarel'           => 383,
        'Riolu'             => 384,
        'Slakoth'           => 385,
        'Vigoroth'          => 386,
        'Wailmer'           => 387,
        'Shinx'             => 388,
        'Luxio'             => 389,
        'Aron'              => 390,
        'Lairon'            => 391,
        'Trapinch'          => 392,
        'Vibrava'           => 393,
        'Feebas'            => 394,
        'Bagon'             => 395,
        'Shelgon'           => 396,
        'Klink'             => 397,
        'Klang'             => 398,
        'Zorua'             => 399,
        'Budew'             => 400,
        'Roselia'           => 401,
        'Drifloon'          => 402,
        'Buneary'           => 403,
        'Shroomish'         => 404,
        'Shuppet'           => 405,
        'Solosis'           => 406,
        'Duosion'           => 407,
        'Cottonee'          => 408,
        'Sandile'           => 409,
        'Krokorok'          => 410,
        'Yamask'            => 411,
        'Joltik'            => 412,
        'Ferroseed'         => 413,
        'Axew'              => 414,
        'Fraxure'           => 415,
        'Golett'            => 416,
        'Fletchling'        => 417,
        'Fletchinder'       => 418,
        'Larvesta'          => 419,
        'Stunfisk'          => 420
    ];

    private Discord $discord;

    /**
     * @param Discord $discord
     */
    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
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
        $option1 = new Option($this->discord, [
            'name'        => 'pokemon1',
            'description' => 'Name of the first pokemon',
            'type'        => Option::STRING
        ]);

        $option2 = new Option($this->discord, [
            'name'        => 'pokemon2',
            'description' => 'Name of the second pokemon',
            'type'        => Option::STRING
        ]);

        $this->discord->application->commands->save(
            $this->discord->application->commands->create(CommandBuilder::new()
                ->setName(self::COMMAND_NAME_FUSE)
                ->setDescription(self::COMMAND_INFO_FUSE)
                ->addOption($option1)
                ->addOption($option2)
                ->toArray()
            )
        );

        $this->discord->application->commands->save(
            $this->discord->application->commands->create(CommandBuilder::new()
                ->setName(self::COMMAND_NAME_FUSE_RANDOM)
                ->setDescription(self::COMMAND_INFO_FUSE_RANDOM)
                ->toArray()
            )
        );
    }

    /**
     * @param Interaction $interaction
     * @param string      $pokemonName1
     * @param string      $pokemonName2
     * @return void
     */
    public function handleFuseCommand(Interaction $interaction, string $pokemonName1, string $pokemonName2): void
    {
        $ids = array_change_key_case(self::IDS);

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
        $pokemonName1 = array_rand(self::IDS);
        $pokemonName2 = array_rand(self::IDS);

        $pokemonId1 = self::IDS[$pokemonName1];
        $pokemonId2 = self::IDS[$pokemonName2];

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