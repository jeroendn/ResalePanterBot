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
    if (file_exists(__DIR__ . '/lock.pid')) {
        echo "PID exits";
    }
    else {
        echo 'Offline';
        $online = false;
    }
    ?></p>

<br>
<br>
<br>
<br>
<a href="https://github.com/jeroendn/ResalePanterBot" target="_blank">https://github.com/jeroendn/ResalePanterBot</a>

<?= Markdown::defaultTransform(file_get_contents('README.md')); ?>

</body>

</html>