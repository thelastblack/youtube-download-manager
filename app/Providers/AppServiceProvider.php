<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Queue::failing(function ($event) {
            $video = $event->job->videoVersion->video;
            echo 'Failed '.$video->id.PHP_EOL;
            $video->status = 'failed';
            $video->save();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
