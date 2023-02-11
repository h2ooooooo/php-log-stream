<?php

use SGI\CopyMachine\CopyMachine;
use SGI\CopyMachine\CopyMachineExclusion;

require_once(__DIR__ . '/../vendor/autoload.php');

$logFile = fopen(__DIR__ . '/../out/logfile.txt', 'a+');

$keepAppending = true;

do {
	$line = sprintf('%s - %s', date('Y-m-d H:i:s'), mt_rand(0x000000, 0xffffff)) . PHP_EOL;

	echo $line;
	fwrite($logFile, $line);

	usleep(mt_rand(100000, 3000000));
} while ($keepAppending);
