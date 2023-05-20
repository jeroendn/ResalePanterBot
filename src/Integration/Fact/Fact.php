<?php

namespace ResalePanterBot\Integration\Fact;

use Discord\Discord;
use ResalePanterBot\Integration\IntegrationInterface;
use ResalePanterBot\Integration\SubProcessInterface;

class Fact implements SubProcessInterface, IntegrationInterface
{
    /**
     * @param Discord $discord
     */
    public function __construct(Discord $discord) {}

    /**
     * @return void
     */
    public function startSubProcess(): void
    {
        $exec = sprintf('%s /dev/null 2>&1 &', 'php src/Integration/Fact/Command/timer.php');

        echo "Starting: $exec \n";
        proc_open($exec, [], $pipes);
    }
}