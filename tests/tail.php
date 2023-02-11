<?php

use jalsoedesign\LogStream\LogStream;

require_once(__DIR__ . '/../vendor/autoload.php');

// Make sure to run "composer run log-writer" in a terminal before running this, so content is added
$outDirectory = __DIR__ . '/../out';

$logStream = LogStream::fromPath($outDirectory . '/logfile.txt');

$logStream->seekLines(-10);

$running = true;

do {
	echo $logStream->readIncremental();

	usleep(100000);
} while ($running);
