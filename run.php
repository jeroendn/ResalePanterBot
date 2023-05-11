<?php

$result = shell_exec('php bot.php 2>&1 > running.log');

echo 'Running bot';

print_r($result);
die;