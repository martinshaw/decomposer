<?php
namespace Martinshaw\Decomposer;

use JsonSerializable;

class VendorDirectory implements JsonSerializable{
    private string $path;
    private int $size;

    public function __construct(string $path, int $size) {
        $this->path = $path;
        $this->size = $size;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getSize(): int {
        return $this->size;
    }

    public function getSizeAsHumanReadable(): string {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->size;
        $unit = 0;

        while ($bytes > 1024) {
            $bytes /= 1024;
            $unit++;
        }

        return round($bytes, 2) . ' ' . $units[$unit];
    }

    public function jsonSerialize() {
        return [
            'path' => $this->path,
            'size' => $this->size,
            'sizeHumanReadable' => $this->getSizeAsHumanReadable()
        ];
    }
}