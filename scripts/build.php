<?php 

$phar = new Phar(__DIR__ . '/../bin/decomposer.phar', 0, 'decomposer');

$phar->startBuffering();

$phar->buildFromDirectory('src');
$phar->buildFromDirectory('vendor');
$phar->addFile('composer.json');
$phar->addFile('composer.lock');

$phar->setStub($phar->createDefaultStub('cli.php'));

$phar->stopBuffering();

echo "PHAR created at bin/decomposer\n";

exit(0);