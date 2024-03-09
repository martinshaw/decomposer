<?php

namespace Martinshaw\Decomposer;

class VendorDirectoryDeleter
{
    private bool $deletedSuccessfully;

    public function getDeletedSuccessfully(): bool
    {
        return $this->deletedSuccessfully;
    }

    public function delete(VendorDirectory $vendorDirectory): void
    {
        $this->deletedSuccessfully = true;
        $this->deleteDirectory($vendorDirectory->getPath());
    }

    private function deleteDirectory(string $path): void
    {
        $scan = scandir($path);
        if ($scan === false) return;

        $files = array_diff($scan, ['.', '..']);
        foreach ($files as $file) {
            $file = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($file)) {
                $this->deleteDirectory($file);
            }
            else {
                if (@unlink($file) !== true) {
                    $this->deletedSuccessfully = false;
                }
            }
        }

        @rmdir($path);
    }
}