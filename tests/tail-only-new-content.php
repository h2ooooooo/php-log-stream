<?php

use jalsoedesign\LogStream\LogStream;

require_once(__DIR__ . '/../vendor/autoload.php');

$resourceDirectory = __DIR__ . '/../res';

$logStream = new LogStream($resourceDirectory . '/logfile.txt');

$logStream->seekEnd();

$running = true;

do {
	echo $logStream->readIncremental();

	usleep(100000);
} while ($running);
