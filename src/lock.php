<?php

$lockFile = fopen(__DIR__ . '../../lock.pid', 'c');
$gotLock  = flock($lockFile, LOCK_EX | LOCK_NB, $wouldBlock);
if ($lockFile === false || (!$gotLock && !$wouldBlock)) {
    throw new Exception("Unexpected error opening or locking lock file. Perhaps you don't  have permission to write to the lock file or its containing directory?");
}
else if (!$gotLock && $wouldBlock) {
    exit("Another instance is already running; terminating.\n");
}

// Lock acquired; let's write our PID to the lock file for the convenience
// of humans who may wish to terminate the script.
ftruncate($lockFile, 0);
fwrite($lockFile, getmypid() . "\n");

//// All done; we blank the PID file and explicitly release the lock
//// (although this should be unnecessary) before terminating.
//ftruncate($lock_file, 0);
//flock($lock_file, LOCK_UN);