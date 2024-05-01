<?php

namespace App\helpers\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Str;

class LocalFileHelper implements FileHelperInterface
{
    public function storeRandomInPublicDirectory(UploadedFile $file): string
    {
        $name = $this->generateRandomName($file->getExtension());

        Storage::disk('public')->move($file->path(), Storage::disk('public')->path($name));

        return $name;
    }

    private function generateRandomName(string $extension): string
    {
        do {
            $name = Str::random(100);
        } while (File::exists($name . $extension));

        return sprintf("%s.%s", $name, $extension);
    }


}