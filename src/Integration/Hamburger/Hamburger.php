<?php

namespace ResalePanterBot\Integration\Hamburger;

use Discord\Discord;
use ResalePanterBot\Integration\IntegrationInterface;
use ResalePanterBot\Integration\SubProcessInterface;

class Hamburger implements SubProcessInterface, IntegrationInterface
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
        $exec = sprintf('%s /dev/null 2>&1 &', '/usr/local/bin/php /var/www/html/src/Integration/Hamburger/Command/timer.php');

        echo "Starting: $exec \n";
        proc_open($exec, [], $pipes);
    }
}