<?php
namespace Martinshaw\Decomposer;

class VendorDirectoriesWalker {
    public function walk($dir) {
        $vendorDirectories = [];
        $composerJsonFiles = [];

        $dir = realpath($dir);
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->getFilename() === 'composer.json') {
                $composerJsonFiles[] = $file->getPathname();
            }
            if ($file->isDir()) {
                if (preg_match('/vendor\/.$/', $file->getPathname())) {
                    $vendorDirectories[] = substr($file->getPathname(), 0, -2);
                }
            }

        }

        return compact('vendorDirectories', 'composerJsonFiles');
    }
}