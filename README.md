# LogStreamer

Simple class that takes a path to a file and gives you the following features:

 - `$stream->readLines(10)` to get the first 10 lines as a string
 - `$stream->getLines(10)` to get the first 10 lines as an array
 - `$stream->readLines(-10)` to get the last 10 lines as a string
 - `$stream->getLines(-10)` to get the last 10 lines as an array
 - `$stream->readIncremental()` to get new content added to the file (as it's written to)

Also gives you a simple way of seeking lines:

 - `$stream->seekLines(10)` to seek to the end of the first 10 lines
 - `$stream->seekLines(-10)` to seek to the beginning of the last 10 lines
 - `$stream->seekReset()` to reset the seek to the first byte
 - `$stream->seekEnd()` to seek to the end

## Examples

### First 10 lines

Read the first 10 lines of a file and print them as text

```
$stream = new \jalsoedesign\LogStream\LogStream('foo.txt');

echo 'The first 10 lines:' . PHP_EOL;
echo $stream->readLines(10);
```

Read the first 10 lines of a file and print them as an array

```
$stream = new \jalsoedesign\LogStream\LogStream('foo.txt');

echo 'The first 10 lines:' . PHP_EOL;
print_r($stream->getLines(10));
```

### Last 10 lines

Read the last 10 lines of a file and print them as text

```
$stream = new \jalsoedesign\LogStream\LogStream('foo.txt');

echo 'The last 10 lines:' . PHP_EOL;
echo $stream->readLines(-10);
```

Read the last 10 lines of a file and print them as an array

```
$stream = new \jalsoedesign\LogStream\LogStream('foo.txt');

echo 'The last 10 lines:' . PHP_EOL;
print_r($stream->getLines(-10));
```

### Tail functionality

Read the last 10 lines of a file and then continue to printing content when the file gets updated

```
$stream = new \jalsoedesign\LogStream\LogStream('foo.txt');

$logStream->seekLines(-10);

$running = true;

echo 'The last 10 lines as well as all new content:' . PHP_EOL;

do {
	echo $logStream->readIncremental();

	usleep(100000);
} while ($running);
```

Open a stream and only print the bytes that have been added to the file after opening

```
$stream = new \jalsoedesign\LogStream\LogStream('foo.txt');

$logStream->seekReset();

$running = true;

echo 'New file content:' . PHP_EOL;

do {
	echo $logStream->readIncremental();

	usleep(100000);
} while ($running);
```
