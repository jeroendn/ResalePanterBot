<?php

namespace ResalePanterBot\Integration;

use Discord\Discord;

interface IntegrationInterface
{
    public function __construct(Discord $discord);
}