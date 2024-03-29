<?php

require_once __DIR__ . '/vendor/autoload.php';

use Michelf\Markdown;

?>
<html lang="EN">

<head>
    <title>Resale Panter</title>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
</head>

<body>

<pre>
<?=
"                                                           /(((/                
                                                            ./((/               
                                                              (///              
                                                               //(/             
                                                               ,//(,            
          (/.            */(((                                 ,(///            
          ,(((/(////(/((((//.                                  //((/            
            ((/(O((((O(((((,                                 //////             
             ///(((/((/((((//          (((/(/(/(//(///((/(/(///((.              
              /(//.(((/(((///(/(/((/(//((/(/(/(/(/((/((/(((/(((*                
                (//(/((/((((((/////////(//(/(/(//((////(/((((/,                 
                  *((((///(//(/(/(((////(/(////((///(/(/(/(/(/                  
                   /(((/((/(((//(////(///(/(((((((/(((/((((.                    
                   ////(/(/(((/(//(//(((//((/(///((/(//((                       
                   //(/(//((/(//(/((/          */(/((/(/((/                     
                /(((////  //((/                 //(/((*(((/(/                   
               /((/(((//////(/              /(/(/(////(/(((/(                   "
?>
</pre>

<p>Status: <?php
    $lockFile = fopen(__DIR__ . '/lock.pid', 'c');
    $gotLock  = flock($lockFile, LOCK_EX | LOCK_NB, $wouldBlock);
    if ($lockFile === false || (!$gotLock && !$wouldBlock)) {
        echo "Failed to check status";
        $online = null;
    }
    elseif (!$gotLock && $wouldBlock) {
        echo 'Online';
        $online = true;
    }
    else {
        echo 'Offline';
        $online = false;
    }
    ?></p>

<?php if ($online === false): ?>
    <button onclick="window.location.href = '/start.php'">Start bot</button>
<?php endif; ?>

<br>
<br>
<br>
<br>
<a href="https://github.com/jeroendn/ResalePanterBot" target="_blank">https://github.com/jeroendn/ResalePanterBot</a>

<?= Markdown::defaultTransform(file_get_contents('README.md')); ?>

</body>

</html>