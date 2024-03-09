<?php
require __DIR__ . '/../vendor/autoload.php';

use Martinshaw\Decomposer\VendorDirectoriesWalker;

$rootPath = empty($argv[1]) ? getcwd() : $argv[1];
$rootPath = realpath($rootPath);

if ($rootPath === false) {
    echo "Invalid path: {$argv[1]}\n";
    exit(1);
}

$walker = new VendorDirectoriesWalker();
$directories = $walker->walk($rootPath);

var_dump($directories);
exit;