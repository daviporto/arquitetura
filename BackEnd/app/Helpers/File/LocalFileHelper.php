<?php

namespace App\Helpers\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LocalFileHelper implements FileHelperInterface
{
    public function storeRandomInPublicDirectory(UploadedFile $file): string
    {
        $name = $this->generateRandomName($file->getClientOriginalExtension());

        $file->move(Storage::disk('public')->path(''), $name);

        return $name;
    }

    public function storeRandomInPrivateDirectory(UploadedFile $file): string
    {
        $name = $this->generateRandomName($file->getClientOriginalExtension());

        $file->move(Storage::disk('local')->path(''), $name);

        return $name;
    }

    public function getUrlForPublicFile(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    public function deletePublicFile(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function deletePrivateFile(string $path): void
    {
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
    }

    public function downloadPrivateFile(string $path): StreamedResponse
    {
        return Storage::disk('local')->download($path);
    }

    private function generateRandomName(string $extension): string
    {
        do {
            $name = Str::random(100);
        } while (File::exists($name . $extension));

        return sprintf("%s.%s", $name, $extension);
    }
}
