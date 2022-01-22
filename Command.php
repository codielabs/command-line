#!/usr/bin/php
<?php

function convert($path, $destination = null, $filetype = null)
{
    try {
        $pathinfo = pathinfo($path);
        $lines = [];

        if (is_dir($path)) {
            throw new Exception('Source is not a file.');
        }

        if (! file_exists($path)) {
            throw new Exception('File not found.');
        }

        $file = fopen($path, 'r');

        if (! $file) {
            throw new Exception('Unable to open file.');
        }

        while (! feof($file)) {
            $lines[] = fgets($file);
        }

        $contents = [];

        foreach ($lines as $line) {
            $contents[] = $line;
        }

        if (is_null($destination) && is_null($filetype)) {
            $destination = '/home/' . get_current_user() . '/' . $pathinfo['filename'] . '.text'; 
        } else if (! is_null($destination) && is_null($filetype)) {
            $destination = $destination;
        } else if (is_null($destination) && ! is_null($filetype)) {
            $destination = '/home/' . get_current_user() . '/' . $pathinfo['filename'] . '.' . $filetype;
        }

        $file = fopen($destination, 'a');

        if (! $file) {
            throw new Exception('Unable to write file.');
        }

        foreach ($contents as $content) {
            fputs($file, $content);
        }

        fclose($file);

        echo "Convert successfully to {$destination}";
        echo "\n";
    } catch (Exception $e) {
        echo $e->getMessage();
        echo "\n";
    }
}

function help()
{
    echo "Usage:  ./Command.php [FILE SOURCES] [OPTIONS]";
    echo "\n";
    echo "Options";
    echo "\n";
    echo "  -t string      Convert file (text or json)";
    echo "\n";
    echo "  -o string      File Destination";
    echo "\n";
    echo "Note: Default destination convert file to user home directory";
    echo "\n";
}

switch ($argc) {
	case 1:
		help();
		break;
	case 2:
        if ($argv[1] == '-h') {
            help();
        } else {
            convert($argv[1]);
        }
		break;
	case 3:
		help();
		break;
	case 4:
        if ($argv[2] == '-t') {
            convert($argv[1], null, $argv[3]);
        } else if ($argv[2] == '-o') {
            convert($argv[1], $argv[3]);
        } else {
            echo "Command not found.";
            echo "\n";
            help();
        }
		break;
	case 5:
		help();
		break;
	case 6:
        if ($argv[2] == '-t' && $argv[4] == '-o') {
            convert($argv[1], $argv[5], $argv[3]);
        } else {
            echo "Command not found.";
            echo "\n";
            help();
        }
		break;
	default:
		help();
		break;
}

die();

/*echo "<pre>";
print_r($argc);
echo "\n";
print_r($argv);
echo "</pre>";
die();*/