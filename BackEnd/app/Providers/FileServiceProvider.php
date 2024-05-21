<?php

namespace App\Providers;

use App\Helpers\File\FileHelperInterface;
use App\Helpers\File\LocalFileHelper;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FileHelperInterface::class, LocalFileHelper::class);
    }
}
