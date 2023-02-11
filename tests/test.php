<?php

use jalsoedesign\LogStream\LogStream;

require_once(__DIR__ . '/../vendor/autoload.php');

$resourceDirectory = __DIR__ . '/../res';

$logStream = new LogStream($resourceDirectory . '/numbers.txt');
$logStreamMultibyte = new LogStream($resourceDirectory . '/multibyte.txt');
$logStreamEmoji = new LogStream($resourceDirectory . '/emojis.txt');

$buffer = '';

$buffer .= ($logStream->readLines(10));
$buffer .=  PHP_EOL;
$buffer .=  PHP_EOL;
$buffer .= ($logStream->readLines(-10));
$buffer .=  PHP_EOL;
$buffer .=  PHP_EOL;

$buffer .= ($logStreamMultibyte->readLines(10));
$buffer .=  PHP_EOL;
$buffer .=  PHP_EOL;
$buffer .= ($logStreamMultibyte->readLines(-10));
$buffer .=  PHP_EOL;
$buffer .=  PHP_EOL;

$buffer .= ($logStreamEmoji->readLines(10));
$buffer .=  PHP_EOL;
$buffer .=  PHP_EOL;
$buffer .= ($logStreamEmoji->readLines(-10));
$buffer .=  PHP_EOL;
$buffer .=  PHP_EOL;

echo $buffer;
file_put_contents($resourceDirectory . '/output.html', $buffer);
