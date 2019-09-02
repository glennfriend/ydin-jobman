<?php
declare(strict_types = 1);
namespace Jobman;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Horizon;
use Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

/**
 *
 */
class JobmanServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (! defined('JOBMAN_PATH')) {
            define('JOBMAN_PATH', dirname(__DIR__));
        }

        $this->registerConfig();
        $this->registerCommands();
        $this->registerSchedule();
    }

    public function boot()
    {
        $this->offerPublishing();
        $this->loadMigrations();
        $this->loadRoutes();

        $this->horizonSecurity();


        /**
         * Log jobs
         *
         * Job dispatched & processing
         */
        /*
        Queue::before(function ( JobProcessing $event ) {
            dd(get_class_methods($event));
        });
        */
    }

    // --------------------------------------------------------------------------------
    //  register
    // --------------------------------------------------------------------------------

    protected function registerConfig()
    {
        $this->mergeConfigFrom(JOBMAN_PATH . '/config/jobman.php', 'jobman');
    }

    protected function registerCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\Commands\TryHelloCommand::class,
            Console\Commands\TypeListCommand::class,
        ]);
    }

    protected function registerSchedule()
    {
        /*
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->job(new HelloWorldTrigger())->daily();
        });
        */
    }

    // --------------------------------------------------------------------------------
    //  boot
    // --------------------------------------------------------------------------------

    /**
     * php artisan vendor:publish
     */
    protected function offerPublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        // job-man && horizon
        $configPrefix = 'jobman';

        $this->publishes([
            JOBMAN_PATH . '/config/' . 'jobman.php'  => config_path('jobman.php'),
            JOBMAN_PATH . '/config/' . 'horizon.php' => config_path('horizon.php'),
        ], $configPrefix);
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(JOBMAN_PATH . '/database/migrations');
    }

    protected function loadRoutes()
    {
        Route::group([
            'namespace' => 'Jobman\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(JOBMAN_PATH . '/routes/web.php');
        });
    }

    protected function horizonSecurity()
    {
        Horizon::auth(function(Request $request) {

            if (in_array($request->ip(), config('jobman.security.allow_ip'))) {
                return true;
            }
            elseif ($this->app->environment('local')) {
                return true;
            }

            die('Security exit !');

            /*
            return (
                in_array($request->ip(), config('jobman.security.allow_ip'))
                || $this->app->environment('local')
                // || auth()->guard('admin')->check()
            );
            */
        });
    }
}
