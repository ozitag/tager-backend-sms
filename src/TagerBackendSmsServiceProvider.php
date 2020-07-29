<?php

namespace OZiTAG\Tager\Backend\Sms;

use Illuminate\Support\ServiceProvider;
use OZiTAG\Tager\Backend\Sms\Console\FlushSmsTemplatesCommand;

class TagerBackendSmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('tager-sms.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                FlushSmsTemplatesCommand::class,
            ]);
        }
    }
}
