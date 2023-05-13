<html lang="EN">

<head>
    <title>Resale Panter</title>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
</head>

<body>

<h1>Resale Panter Discord bot</h1>
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
    $online   = false;
    $lockFile = fopen(__DIR__ . '/lock.pid', 'c');
    $gotLock  = flock($lockFile, LOCK_EX | LOCK_NB, $wouldBlock);
    if ($lockFile === false || (!$gotLock && !$wouldBlock)) {
        echo "Failed to check status";
    }
    elseif (!$gotLock && $wouldBlock) {
        echo 'Online';
        $online = true;
    }
    else {
        echo 'Offline';
    }
    ?></p>

<?php if ($online): ?>
    <button onclick="window.location.href = 'https://bot.jeroendn.nl/start.php'">Start bot</button>
<?php endif; ?>

<br>
<br>
<br>
<br>
<a href="https://github.com/jeroendn/ResalePanterBot" target="_blank">https://github.com/jeroendn/ResalePanterBot</a>

</body>

</html>