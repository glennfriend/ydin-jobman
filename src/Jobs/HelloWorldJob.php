<?php
declare(strict_types = 1);
namespace Jobman\Jobs;

use Exception;
use Jobman\Jobs\BaseJob;

/**
 * say hello job
 *
 * example
 *      php artsan jobman:try && php artsan horizon
 *
 */
class HelloWorldJob extends BaseJob
{
    /**
     * @var string
     */
    public $queue = 'hello-world';

    /**
     * @throws Exception
     */
    public function handle()
    {
        try {
            $this->beforeHandle();
            $this->perform();
            $this->afterHandle();
        }
        catch (Exception $e) {
            $this->saveException($e);
            throw $e;
        }
    }

    // --------------------------------------------------------------------------------
    //  main
    // --------------------------------------------------------------------------------

    protected function perform()
    {
        $this->start();
        $this->process();
        $this->final();
    }

    protected function start()
    {
        $this->log('start');

        echo "{$this->jobmanId} => hello world" . "\n";
    }

    protected function process()
    {

    }

    protected function final()
    {
        echo "{$this->jobmanId} => final" . "\n";
    }

}
