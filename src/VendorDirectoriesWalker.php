<?php
namespace Martinshaw\Decomposer;

class VendorDirectoriesWalker {
    public function walk(string $rootPath) {
        $directories = [];

        $rootPath = realpath($rootPath);

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($rootPath));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'composer.json') {
                $directories[dirname($file->getPathname()) . DIRECTORY_SEPARATOR . 'vendor']['hasComposerJson'] = true;
            }

            if ($file->isDir()) {
                if (preg_match('/vendor\/.$/', $file->getPathname())) {
                    $directories[substr($file->getPathname(), 0, -2)]['isVendorDirectory'] = true;
                }
            }
            
            if ($file->isFile()) {
                $pathParts = explode(DIRECTORY_SEPARATOR, $file->getPathname());
                foreach ($pathParts as $index => $part) {
                    $path = implode(DIRECTORY_SEPARATOR, array_slice($pathParts, 0, $index + 1));
                    if (isset($directories[$path]) === false) continue;

                    if (isset($directories[$path]['size']) === false) $directories[$path]['size'] = 0;
                    $directories[$path]['size'] += $file->getSize();        
                }
            }
        }

        $directories = array_filter(
            $directories, 
            function($directory) {
                return count($directory) === 3;
            }
        );

        $directories = array_map(
            function ($path) use ($directories) {
                return new VendorDirectory(
                    $path,
                    $directories[$path]['size']
                );
            }, 
            array_keys($directories)
        );

        return $directories;
    }
}