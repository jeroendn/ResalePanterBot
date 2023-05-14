<?php

namespace ResalePanterBot\Integration;

use Discord\Discord;

interface CommandInterface
{
    public function __construct(Discord $discord);

    public function getHelpString(): string;

    public function registerCommands(): void;
}