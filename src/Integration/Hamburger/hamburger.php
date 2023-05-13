<?php
/**
 * @var Discord $discord
 */

use Discord\Discord;

$discord->on('ready', function (Discord $discord) {

    $exec = sprintf('%s /dev/null 2>&1 &', 'php src/Integration/Hamburger/Command/timer.php');

    echo "Attempt: $exec \n";
    proc_open($exec, [], $pipes);

});