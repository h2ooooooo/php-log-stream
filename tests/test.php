<?php

use jalsoedesign\LogStream\LogStream;

require_once(__DIR__ . '/../vendor/autoload.php');

$resourceDirectory = __DIR__ . '/../res';

$logStream = LogStream::fromPath($resourceDirectory . '/numbers.txt');
$logStreamMultibyte = LogStream::fromPath($resourceDirectory . '/multibyte.txt');
$logStreamEmoji = LogStream::fromPath($resourceDirectory . '/emojis.txt');

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

// In order to read the emojis correctly we'll have to store them in a format the computer can display
file_put_contents($resourceDirectory . '/output.html', $buffer);
