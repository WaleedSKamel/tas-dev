<?php

namespace App\Providers;

use App\Interfaces\BaseRepository\BaseRepositoryInterface;
use App\Interfaces\UploadFile\UploadFileRepositoryInterface;
use App\Repositories\BaseRepository\BaseRepository;
use App\Repositories\UploadFile\UploadFileRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UploadFileRepositoryInterface::class, UploadFileRepository::class);
    }
}
