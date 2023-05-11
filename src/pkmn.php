<?php
/**
 * @var Discord $discord
 */

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

const IDS = [
    "bulbasaur"           => 1
    , "ivysaur"           => 2
    , "venusaur"          => 3
    , "charmander"        => 4
    , "charmeleon"        => 5
    , "charizard"         => 6
    , "squirtle"          => 7
    , "wartortle"         => 8
    , "blastoise"         => 9
    , "caterpie"          => 10
    , "metapod"           => 11
    , "butterfree"        => 12
    , "weedle"            => 13
    , "kakuna"            => 14
    , "beedrill"          => 15
    , "pidgey"            => 16
    , "pidgeotto"         => 17
    , "pidgeot"           => 18
    , "rattata"           => 19
    , "raticate"          => 20
    , "spearow"           => 21
    , "fearow"            => 22
    , "ekans"             => 23
    , "arbok"             => 24
    , "pikachu"           => 25
    , "raichu"            => 26
    , "sandshrew"         => 27
    , "sandslash"         => 28
    , "nidoran-f"         => 29
    , "nidorina"          => 30
    , "nidoqueen"         => 31
    , "nidoran-m"         => 32
    , "nidorino"          => 33
    , "nidoking"          => 34
    , "clefairy"          => 35
    , "clefable"          => 36
    , "vulpix"            => 37
    , "ninetales"         => 38
    , "jigglypuff"        => 39
    , "wigglytuff"        => 40
    , "zubat"             => 41
    , "golbat"            => 42
    , "oddish"            => 43
    , "gloom"             => 44
    , "vileplume"         => 45
    , "paras"             => 46
    , "parasect"          => 47
    , "venonat"           => 48
    , "venomoth"          => 49
    , "diglett"           => 50
    , "dugtrio"           => 51
    , "meowth"            => 52
    , "persian"           => 53
    , "psyduck"           => 54
    , "golduck"           => 55
    , "mankey"            => 56
    , "primeape"          => 57
    , "growlithe"         => 58
    , "arcanine"          => 59
    , "poliwag"           => 60
    , "poliwhirl"         => 61
    , "poliwrath"         => 62
    , "abra"              => 63
    , "kadabra"           => 64
    , "alakazam"          => 65
    , "machop"            => 66
    , "machoke"           => 67
    , "machamp"           => 68
    , "bellsprout"        => 69
    , "weepinbell"        => 70
    , "victreebel"        => 71
    , "tentacool"         => 72
    , "tentacruel"        => 73
    , "geodude"           => 74
    , "graveler"          => 75
    , "golem"             => 76
    , "ponyta"            => 77
    , "rapidash"          => 78
    , "slowpoke"          => 79
    , "slowbro"           => 80
    , "magnemite"         => 81
    , "magneton"          => 82
    , "farfetchd"         => 83
    , "doduo"             => 84
    , "dodrio"            => 85
    , "seel"              => 86
    , "dewgong"           => 87
    , "grimer"            => 88
    , "muk"               => 89
    , "shellder"          => 90
    , "cloyster"          => 91
    , "gastly"            => 92
    , "haunter"           => 93
    , "gengar"            => 94
    , "onix"              => 95
    , "drowzee"           => 96
    , "hypno"             => 97
    , "krabby"            => 98
    , "kingler"           => 99
    , "voltorb"           => 100
    , "electrode"         => 101
    , "exeggcute"         => 102
    , "exeggutor"         => 103
    , "cubone"            => 104
    , "marowak"           => 105
    , "hitmonlee"         => 106
    , "hitmonchan"        => 107
    , "lickitung"         => 108
    , "koffing"           => 109
    , "weezing"           => 110
    , "rhyhorn"           => 111
    , "rhydon"            => 112
    , "chansey"           => 113
    , "tangela"           => 114
    , "kangaskhan"        => 115
    , "horsea"            => 116
    , "seadra"            => 117
    , "goldeen"           => 118
    , "seaking"           => 119
    , "staryu"            => 120
    , "starmie"           => 121
    , "mr-mime"           => 122
    , "scyther"           => 123
    , "jynx"              => 124
    , "electabuzz"        => 125
    , "magmar"            => 126
    , "pinsir"            => 127
    , "tauros"            => 128
    , "magikarp"          => 129
    , "gyarados"          => 130
    , "lapras"            => 131
    , "ditto"             => 132
    , "eevee"             => 133
    , "vaporeon"          => 134
    , "jolteon"           => 135
    , "flareon"           => 136
    , "porygon"           => 137
    , "omanyte"           => 138
    , "omastar"           => 139
    , "kabuto"            => 140
    , "kabutops"          => 141
    , "aerodactyl"        => 142
    , "snorlax"           => 143
    , "articuno"          => 144
    , "zapdos"            => 145
    , "moltres"           => 146
    , "dratini"           => 147
    , "dragonair"         => 148
    , "dragonite"         => 149
    , "mewtwo"            => 150
    , "mew"               => 151
    , "chikorita"         => 152
    , "bayleef"           => 153
    , "meganium"          => 154
    , "cyndaquil"         => 155
    , "quilava"           => 156
    , "typhlosion"        => 157
    , "totodile"          => 158
    , "croconaw"          => 159
    , "feraligatr"        => 160
    , "sentret"           => 161
    , "furret"            => 162
    , "hoothoot"          => 163
    , "noctowl"           => 164
    , "ledyba"            => 165
    , "ledian"            => 166
    , "spinarak"          => 167
    , "ariados"           => 168
    , "crobat"            => 169
    , "chinchou"          => 170
    , "lanturn"           => 171
    , "pichu"             => 172
    , "cleffa"            => 173
    , "igglybuff"         => 174
    , "togepi"            => 175
    , "togetic"           => 176
    , "natu"              => 177
    , "xatu"              => 178
    , "mareep"            => 179
    , "flaaffy"           => 180
    , "ampharos"          => 181
    , "bellossom"         => 182
    , "marill"            => 183
    , "azumarill"         => 184
    , "sudowoodo"         => 185
    , "politoed"          => 186
    , "hoppip"            => 187
    , "skiploom"          => 188
    , "jumpluff"          => 189
    , "aipom"             => 190
    , "sunkern"           => 191
    , "sunflora"          => 192
    , "yanma"             => 193
    , "wooper"            => 194
    , "quagsire"          => 195
    , "espeon"            => 196
    , "umbreon"           => 197
    , "murkrow"           => 198
    , "slowking"          => 199
    , "misdreavus"        => 200
    , "unown"             => 201
    , "wobbuffet"         => 202
    , "girafarig"         => 203
    , "pineco"            => 204
    , "forretress"        => 205
    , "dunsparce"         => 206
    , "gligar"            => 207
    , "steelix"           => 208
    , "snubbull"          => 209
    , "granbull"          => 210
    , "qwilfish"          => 211
    , "scizor"            => 212
    , "shuckle"           => 213
    , "heracross"         => 214
    , "sneasel"           => 215
    , "teddiursa"         => 216
    , "ursaring"          => 217
    , "slugma"            => 218
    , "magcargo"          => 219
    , "swinub"            => 220
    , "piloswine"         => 221
    , "corsola"           => 222
    , "remoraid"          => 223
    , "octillery"         => 224
    , "delibird"          => 225
    , "mantine"           => 226
    , "skarmory"          => 227
    , "houndour"          => 228
    , "houndoom"          => 229
    , "kingdra"           => 230
    , "phanpy"            => 231
    , "donphan"           => 232
    , "porygon2"          => 233
    , "stantler"          => 234
    , "smeargle"          => 235
    , "tyrogue"           => 236
    , "hitmontop"         => 237
    , "smoochum"          => 238
    , "elekid"            => 239
    , "magby"             => 240
    , "miltank"           => 241
    , "blissey"           => 242
    , "raikou"            => 243
    , "entei"             => 244
    , "suicune"           => 245
    , "larvitar"          => 246
    , "pupitar"           => 247
    , "tyranitar"         => 248
    , "lugia"             => 249
    , "ho-oh"             => 250
    , "celebi"            => 251
    , "Azurill"           => 252
    , "Wynaut"            => 253
    , "Ambipom"           => 254
    , "Mismagius"         => 255
    , "Honchkrow"         => 256
    , "Bonsly"            => 257
    , "Mime-jr"           => 258
    , "Happiny"           => 259
    , "Munchlax"          => 260
    , "Mantyke"           => 261
    , "Weavile"           => 262
    , "Magnezone"         => 263
    , "Lickilicky"        => 264
    , "Rhyperior"         => 265
    , "Tangrowth"         => 266
    , "Electivire"        => 267
    , "Magmortar"         => 268
    , "Togekiss"          => 269
    , "Yanmega"           => 270
    , "Leafeon"           => 271
    , "Glaceon"           => 272
    , "Gliscor"           => 273
    , "Mamoswine"         => 274
    , "Porygon-z"         => 275
    , "Treecko"           => 276
    , "Grovyle"           => 277
    , "Sceptile"          => 278
    , "Torchic"           => 279
    , "Combusken"         => 280
    , "Blaziken"          => 281
    , "Mudkip"            => 282
    , "Marshtomp"         => 283
    , "Swampert"          => 284
    , "Ralts"             => 285
    , "Kirlia"            => 286
    , "Gardevoir"         => 287
    , "Gallade"           => 288
    , "Shedinja"          => 289
    , "Kecleon"           => 290
    , "Beldum"            => 291
    , "Metang"            => 292
    , "Metagross"         => 293
    , "Bidoof"            => 294
    , "Spiritomb"         => 295
    , "Lucario"           => 296
    , "Gible"             => 297
    , "Gabite"            => 298
    , "Garchomp"          => 299
    , "Mawile"            => 300
    , "Lileep"            => 301
    , "Cradily"           => 302
    , "Anorith"           => 303
    , "Armaldo"           => 304
    , "Cranidos"          => 305
    , "Rampardos"         => 306
    , "Shieldon"          => 307
    , "Bastiodon"         => 308
    , "Slaking"           => 309
    , "Absol"             => 310
    , "Duskull"           => 311
    , "Dusclops"          => 312
    , "Dusknoir"          => 313
    , "Wailord"           => 314
    , "Arceus"            => 315
    , "Turtwig"           => 316
    , "Grotle"            => 317
    , "Torterra"          => 318
    , "Chimchar"          => 319
    , "Monferno"          => 320
    , "Infernape"         => 321
    , "Piplup"            => 322
    , "Prinplup"          => 323
    , "Empoleon"          => 324
    , "Nosepass"          => 325
    , "Probopass"         => 326
    , "Honedge"           => 327
    , "Doublade"          => 328
    , "Aegislash-shield"  => 329
    , "Pawniard"          => 330
    , "Bisharp"           => 331
    , "Luxray"            => 332
    , "Aggron"            => 333
    , "Flygon"            => 334
    , "Milotic"           => 335
    , "Salamence"         => 336
    , "Klinklang"         => 337
    , "Zoroark"           => 338
    , "Sylveon"           => 339
    , "Kyogre"            => 340
    , "Groudon"           => 341
    , "Rayquaza"          => 342
    , "Dialga"            => 343
    , "Palkia"            => 344
    , "Giratina-altered"  => 345
    , "Regigigas"         => 346
    , "Darkrai"           => 347
    , "Genesect"          => 348
    , "Reshiram"          => 349
    , "Zekrom"            => 350
    , "Kyurem"            => 351
    , "Roserade"          => 352
    , "Drifblim"          => 353
    , "Lopunny"           => 354
    , "Breloom"           => 355
    , "Ninjask"           => 356
    , "Banette"           => 357
    , "Rotom"             => 358
    , "Reuniclus"         => 359
    , "Whimsicott"        => 360
    , "Krookodile"        => 361
    , "Cofagrigus"        => 362
    , "Galvantula"        => 363
    , "Ferrothorn"        => 364
    , "Litwick"           => 365
    , "Lampent"           => 366
    , "Chandelure"        => 367
    , "Haxorus"           => 368
    , "Golurk"            => 369
    , "Pyukumuku"         => 370
    , "Klefki"            => 371
    , "Talonflame"        => 372
    , "Mimikyu-disguised" => 373
    , "Volcarona"         => 374
    , "Deino"             => 375
    , "Zweilous"          => 376
    , "Hydreigon"         => 377
    , "Latias"            => 378
    , "Latios"            => 379
    , "Deoxys-normal"     => 380
    , "Jirachi"           => 381
    , "Nincada"           => 382
    , "Bibarel"           => 383
    , "Riolu"             => 384
    , "Slakoth"           => 385
    , "Vigoroth"          => 386
    , "Wailmer"           => 387
    , "Shinx"             => 388
    , "Luxio"             => 389
    , "Aron"              => 390
    , "Lairon"            => 391
    , "Trapinch"          => 392
    , "Vibrava"           => 393
    , "Feebas"            => 394
    , "Bagon"             => 395
    , "Shelgon"           => 396
    , "Klink"             => 397
    , "Klang"             => 398
    , "Zorua"             => 399
    , "Budew"             => 400
    , "Roselia"           => 401
    , "Drifloon"          => 402
    , "Buneary"           => 403
    , "Shroomish"         => 404
    , "Shuppet"           => 405
    , "Solosis"           => 406
    , "Duosion"           => 407
    , "Cottonee"          => 408
    , "Sandile"           => 409
    , "Krokorok"          => 410
    , "Yamask"            => 411
    , "Joltik"            => 412
    , "Ferroseed"         => 413
    , "Axew"              => 414
    , "Fraxure"           => 415
    , "Golett"            => 416
    , "Fletchling"        => 417
    , "Fletchinder"       => 418
    , "Larvesta"          => 419
    , "Stunfisk"          => 420
];

$discord->on('ready', function (Discord $discord) {

    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {

        try {

            if (preg_match('/(fuse)\s+(\w+)\s+(\w+)/', $message->content, $matches)) {

                $ids = array_change_key_case(IDS);

                $pkmn1Id = $ids[strtolower($matches[2])];
                $pkmn2Id = $ids[strtolower($matches[3])];

                replyFuse($message, $pkmn1Id, $pkmn2Id);
            }
            elseif ($message->content === 'fuse') {
                replyRandomFuse($message);
            }

        }
        catch (Throwable $e) {
            $message->reply('Oeps er ging iets mis: ' . $e->getMessage());
        }

    });

});

/**
 * @param Message $message
 * @param int     $pkmn1
 * @param int     $pkmn2
 * @return void
 */
function replyFuse(Message $message, int $pkmn1, int $pkmn2): void
{
    $imgUrl1 = "https://raw.githubusercontent.com/Aegide/custom-fusion-sprites/main/CustomBattlers/$pkmn1.$pkmn2.png";
    $imgUrl2 = "https://raw.githubusercontent.com/Aegide/custom-fusion-sprites/main/CustomBattlers/$pkmn2.$pkmn1.png";

    $result1 = @file_get_contents($imgUrl1);
    $result2 = @file_get_contents($imgUrl2);

    if ($result1 === false && $result2 === false) {
        $message->reply('Oeps, geen sprites beschikbaar! Probeer eens een andere combinatie.');
        return;
    }

    if ($result1 !== false) {
        $message->reply($imgUrl1);
    }

    if ($result2 !== false) {
        $message->reply($imgUrl2);
    }
}

/**
 * @param Message $message
 * @return void
 */
function replyRandomFuse(Message $message): void
{
    $pkmn1Name = array_rand(IDS);
    $pkmn2Name = array_rand(IDS);

    $pkmn1Id = IDS[$pkmn1Name];
    $pkmn2Id = IDS[$pkmn2Name];

    $imgUrl1 = "https://raw.githubusercontent.com/Aegide/custom-fusion-sprites/main/CustomBattlers/$pkmn1Id.$pkmn2Id.png";
    $imgUrl2 = "https://raw.githubusercontent.com/Aegide/custom-fusion-sprites/main/CustomBattlers/$pkmn2Id.$pkmn1Id.png";

    $result1 = @file_get_contents($imgUrl1);
    $result2 = @file_get_contents($imgUrl2);

    if ($result1 === false && $result2 === false) {
        replyRandomFuse($message); // Retry
        return;
    }

    if ($result1 !== false) {
        $message->reply("$pkmn1Name/$pkmn2Name $imgUrl1");
    }

    if ($result2 !== false) {
        $message->reply("$pkmn2Name/$pkmn1Name $imgUrl2");
    }
}