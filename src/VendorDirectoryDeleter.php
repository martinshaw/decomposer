<?php

namespace Martinshaw\Decomposer;

class VendorDirectoryDeleter
{
    public function delete(VendorDirectory $vendorDirectory): void
    {
        $this->deleteDirectory($vendorDirectory->getPath());
    }

    private function deleteDirectory(string $path): void
    {
        $scan = scandir($path);
        if ($scan === false) return;

        $files = array_diff($scan, ['.', '..']);
        foreach ($files as $file) {
            $file = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($file)) $this->deleteDirectory($file);
            else unlink($file);
        }
        rmdir($path);
    }
}