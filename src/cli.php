<?php

// When run as a global composer bin, we want to use the global composer autoloader
if (file_exists(__DIR__ . '/../vendor/autoload.php')) require __DIR__ . '/../vendor/autoload.php';
else if (file_exists(__DIR__ . '/../../autoload.php')) require __DIR__ . '/../../autoload.php';
else if (file_exists(__DIR__ . '/../../../autoload.php')) require __DIR__ . '/../../../autoload.php';
else {
    echo "Could not find composer autoloader\n";
    exit(1);
}

use Martinshaw\Decomposer\UI\Application;
use Martinshaw\Decomposer\VendorDirectoryDeleter;
use Martinshaw\Decomposer\VendorDirectoriesWalker;

if (in_array('--help', $argv) || in_array('-h', $argv)) {
    echo "Usage: " . $argv[0] . " [path] [--all]\n";
    echo "  path: The path to the root of projects whose vendor directories you wish to delete\n";
    echo "  --all: Automatically delete all vendor directories without interaction (optional)\n";
    echo "  --help: Display this help information\n";
    echo "\n";
    echo "If no path is provided, the current working directory will be used\n";
    echo "If the --all flag is not provided, you will be prompted to confirm the deletion of each vendor directory\n";
    exit(0);
}

$rootPath = empty($argv[1]) || $argv[1] === '--all' ? getcwd() : $argv[1];
$rootPath = realpath($rootPath);

if ($rootPath === false) {
    echo "Invalid path: {$argv[1]}\n";
    exit(1);
}

if (in_array('--all', $argv)) {
    $walker = new VendorDirectoriesWalker();
    $directories = $walker->walk($rootPath);

    foreach ($directories as $directory) {
        echo "Deleting {$directory->getPath()}...  ";
        $deleter = new VendorDirectoryDeleter();
        $deleter->delete($directory);

        if ($deleter->getDeletedSuccessfully()) echo "Done\n";
        else echo "Failed to delete {$directory->getPath()}\n";
    }

    exit(0);
}

$ui = new Application($rootPath);
$ui->run();