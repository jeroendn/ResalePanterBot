<?php
/**
 * Script to manually start the bot in case it's not being run by the cronjob.
 */

$result = shell_exec('php bot.php 2>&1 > running.log');

die('Bot is running');