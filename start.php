<?php
/**
 * Script to manually start the bot in case it's not being run by the cronjob.
 */

$result = proc_open('php bot.php 2>&1 > /logs/bot.log', [], $pipes);

die('Bot is running');