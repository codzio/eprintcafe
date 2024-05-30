<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\FlysystemDropbox\DropboxAdapter;

class DropboxServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('dropbox', function ($app) {
            $token = config('filesystems.disks.dropbox.token');

            //$adapter = new SpatieDropboxAdapter($token);
            //$adapter = new \Spatie\FlysystemDropbox\DropboxAdapter($token);
            $adapter = new DropboxAdapter($token);
            return new Filesystem($adapter);
        });
    }

    public function boot()
    {
        //
    }
}
