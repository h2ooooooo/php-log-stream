<?php

namespace jalsoedesign\LogStream;

/**
 * Class CopyMachine
 *
 * A class to copy and mirror files and directories, with options to exclude certain files and directories.
 */
class LogStream {
	/**
	 * @param string $path The path to the file
	 *
	 * @return LogStream The stream created from the handle
	 *
	 * @throws \Exception Throws an exception if the handle is not readable
	 */
	public static function fromPath(string $path) : LogStream {
		$handle = fopen($path, 'r');

		return new LogStream($handle);
	}

	/** @var resource $handle */
	private $handle;

	/**
	 * @param resource $handle A resource from fopen()
	 *
	 * @throws \Exception Throws an exception if the handle is not readable
	 */
	public function __construct($handle) {
		if ($handle === false) {
			throw new \Exception('Handle was not readable');
		}

		$this->handle = $handle;
	}

	/**
	 * Closes the handle to the file
	 *
	 * @return bool The result from fclose
	 */
	public function close() : bool {
		return fclose($this->handle);
	}

	/**
	 * @param int $bufferSize
	 *
	 * @return string
	 */
	public function readAll(int $bufferSize = 8192) : string {
		// Start by seeking to the very start, then read incrementally
		$this->seekReset();

		return $this->readIncremental($bufferSize);
	}

	/**
	 * @param int $bufferSize
	 *
	 * @return string
	 */
	public function readIncremental(int $bufferSize = 8192) : string {
		$allBuffer = '';

		do {
			$bufferString = fread($this->handle, $bufferSize);

			if ($bufferString !== false) {
				$allBuffer .= $bufferString;
			}
		} while (!feof($this->handle));

		return $allBuffer;
	}

	/**
	 * Reads lines either from the start of the file or the end
	 *
	 * @param int $lineCount How many lines you want returned (use a negative number to start from the end)
	 * @param bool $includeEmptyLines Whether to include empty lines or skip them
	 *
	 * @return string
	 */
	public function readLines(int $lineCount, bool $includeEmptyLines = true) : string {
		$lines = $this->getLines($lineCount, $includeEmptyLines);

		return implode(PHP_EOL, $lines);
	}

	/**
	 * @param int $lineCount
	 *
	 * @return int The current handle position after seeking
	 */
	public function seekLines(int $lineCount) : int {
		// This will seek for us - just don't return the result
		$this->getLines($lineCount, false);

		$pos = ftell($this->handle);

		do {
			$character = fgetc($this->handle);

			$isLineSwitchCharacter = $character === "\r" || $character === "\n" || $character === false;

			if ($isLineSwitchCharacter) {
				$pos += 1;
			}

			fseek($this->handle, $pos, SEEK_SET);
		} while ($isLineSwitchCharacter);

		return $pos;
	}

	/**
	 * Resets the current handle position to the very beginning of the file
	 *
	 * @return int The current handle position after seeking
	 */
	public function seekReset() : int {
		return fseek($this->handle, 0, SEEK_SET);
	}

	/**
	 * Updates the current handle position to go to the very end of the file
	 *
	 * @return int The current handle position after seeking
	 */
	public function seekEnd() : int {
		return fseek($this->handle, 0, SEEK_END);
	}

	/**
	 * Reads lines either from the start of the file or the end
	 *
	 * @param int $lineCount How many lines you want returned (use a negative number to start from the end)
	 * @param bool $includeEmptyLines Whether to include empty lines or skip them
	 *
	 * @return string[]
	 */
	public function getLines(int $lineCount, bool $includeEmptyLines = true) : array {
		if ($lineCount < 0) {
			// Read last lines
			fseek($this->handle, -1, SEEK_END);

			$pos = ftell($this->handle);

			$seekDirection = -1;
		} else {
			$pos = 0;

			$seekDirection = +1;
		}

		$lineCountAbs = abs($lineCount);


		$lineCountBuffer = 0;

		$lines = [];

		// Start by stripping off newline characters from beginning
		do {
			fseek($this->handle, $pos, SEEK_SET);

			$character = fgetc($this->handle);

			$isLineSwitchCharacter = $character === "\r" || $character === "\n" || $character === false;

			if ($isLineSwitchCharacter) {
				$pos += $seekDirection;
			}
		} while ($isLineSwitchCharacter);

		do {
			$characterBuffer = [];

			// Find characters
			do {
				fseek($this->handle, $pos, SEEK_SET);
				$character = fgetc($this->handle);

				$isLineSwitchCharacter = $character === "\r" || $character === "\n" || $character === false;

				if (!$isLineSwitchCharacter) {
					$characterBuffer[] = $character;
				}

				$pos += $seekDirection;
			} while ( ! $isLineSwitchCharacter);

			// Continue until no line switch characters were found
			do {
				fseek($this->handle, $pos, SEEK_SET);

				$character = fgetc($this->handle);

				$isLineSwitchCharacter = $character === "\r" || $character === "\n" || $character === false;

				if ($isLineSwitchCharacter) {
					$pos += $seekDirection;
				}
			} while ($isLineSwitchCharacter);

			if ($lineCount < 0) {
				$characterBuffer = array_reverse($characterBuffer);
			}

			$lineBuffer = mb_convert_encoding(implode('', $characterBuffer), 'UTF-8');

			if ($includeEmptyLines || $lineBuffer !== '') {
				$lines[] = $lineBuffer;
				$lineCountBuffer++;
			}
		} while ($lineCountBuffer < $lineCountAbs || $character === false);

		return $lines;
	}
}
