<?php

use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;

/**
 * @param Interaction $interaction
 * @param string      $errorMessage
 * @return void
 */
function respondWithError(Interaction $interaction, string $errorMessage = 'Unknown error occurred'): void
{
    $message = MessageBuilder::new()->setContent("Oops! $errorMessage");

    try {
        $interaction->respondWithMessage($message);
    }
    catch (Throwable $e) {
        echo $e->getMessage();
    }
}