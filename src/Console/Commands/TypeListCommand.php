<?php
namespace Jobman\Console\Commands;

use Illuminate\Console\Command;

class TypeListCommand extends Command
{
    /**
     *
     */
    protected $signature = 'jobman:type-list';

    /**
     *
     */
    protected $description = '';

    /**
     *
     */
    public function handle()
    {
        echo 'horizon.use = ' . config('horizon.use');
        echo "\n\n";

        /*
        $jobs = config('jobman.jobs');
        dump($jobs);
        echo "\n\n";
        */
    }

}
