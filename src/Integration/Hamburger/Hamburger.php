<?php

namespace ResalePanterBot\Integration\Hamburger;

use Discord\Discord;
use ResalePanterBot\Integration\IntegrationInterface;

class Hamburger implements IntegrationInterface
{
    /**
     * @param Discord $discord
     */
    public function __construct(Discord $discord) {}

    /**
     * @return void
     */
    public function startTimerProcess(): void
    {
        $exec = sprintf('%s /dev/null 2>&1 &', 'php src/Integration/Hamburger/Command/timer.php');

        echo "Attempt: $exec \n";
        proc_open($exec, [], $pipes);
    }
}