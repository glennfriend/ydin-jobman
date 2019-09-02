<?php
namespace Jobman\Console\Commands;

use Illuminate\Console\Command;
use Jobman\Dispatch\JobDispatch;
use Jobman\Jobman;

/**
 * Class TryHelloCommand
 * @package Jobman\Console\Commands
 */
class TryHelloCommand extends Command
{
    /**
     *
     */
    protected $signature = 'jobman:try-hello-world';

    /**
     *
     */
    protected $description = '';

    /**
     *
     */
    public function handle(Jobman $jobman)
    {
        $data = [
            'name'    => 'try-hello-world-command',
            'message' => 'hello at ' . date('c'),
        ];

        echo 'create data, send to dispatch. ';

        $jobmanId = $jobman->push('hello-world', $data);
        echo 'id = ' . $jobmanId . "\n";
    }

}
