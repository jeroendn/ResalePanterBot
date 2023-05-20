<?php

namespace ResalePanterBot\Integration;

interface CommandInterface
{
    public function getHelpString(): string;

    public function registerCommands(): void;
}