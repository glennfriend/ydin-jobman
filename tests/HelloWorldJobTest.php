<?php
use Tests\TestCase;
use Jobman\Jobs\HelloWorldJob;

/**
 * Class HelloWorldJobTest
 */
final class HelloWorldJobTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->job = $this->mock(HelloWorldJob::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $this->job->id = 1;
    }

    /**
     *
     */
    public function test_call_HelloWorld_job()
    {
        $this->job->shouldReceive('log');
        $this->job->shouldReceive('beforeHandle');
        $this->job->shouldReceive('afterHandle');

        $this->job->shouldReceive('perform');
        $this->job->handle();
    }

}


