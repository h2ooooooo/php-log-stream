<?php

use SGI\CopyMachine\CopyMachine;
use SGI\CopyMachine\CopyMachineExclusion;

require_once(__DIR__ . '/../vendor/autoload.php');

$logFile = fopen(__DIR__ . '/numbers.txt', 'w+');

for ($i = 1; $i <= 10000; $i++) {
	fwrite($logFile, $i . PHP_EOL);
}

fclose($logFile);
