<?php
declare(strict_types = 1);
namespace Jobman\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use Jobman\Utility\Structure\ExceptionWrap;
use Jobman\Entities\Eloquent\JobmanEloquent;


class BaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $jobmanId;

    /**
     * @var JobmanEloquent
     */
    public $jobman;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $jobmanId)
    {
        $this->jobmanId = $jobmanId;
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------

    protected function beforeHandle()
    {
        $this->jobman = $this->getJobman();

        // Jobman 如果資料本身不存在
        // 那麼就沒有運行的必要, 程式直接中止
        if (! $this->jobman) {
            die('Jobman not found !');
        }

        // jobman 有可能執行一次以上, 有些值要重新設定
        $this->resetExecuteJobman();
    }

    protected function afterHandle()
    {
        $this->jobman->status       = 'complete';
        $this->jobman->completed_at = date('c');
        $this->jobman->save();
    }

    /**
     * @param string $message
     */
    protected function log(string $message)
    {
        $date = date('m-d H:i:s');
        $log = $date . ' - ' . $message;
        $this->jobman->logs .= $log . "\n";
    }

    /**
     * @param string $type
     * @param string $message
     */
    /*
    protected function systemLog(string $type, string $message)
    {
        $fullClassName = get_called_class();
        $tmp = explode("\\", $fullClassName);
        $className = $tmp[count($tmp)-1];

        $idInfo = '';
        if (isset($this->jobmanId)) {
            $idInfo = 'jobman=' . $this->jobmanId . ' - ';
        }

        Log::$type("{$className}: {$idInfo}{$message}");
    }
    */

    protected function resetExecuteJobman()
    {
        $this->jobman->status           = null;
        $this->jobman->error_message    = null;
        $this->jobman->exception        = null;
        $this->jobman->completed_code   = null;
        $this->jobman->completed_at     = null;
        $this->jobman->failed_at        = null;
        $this->jobman->executed_total++;
        $this->jobman->executed_at      = date('c');

    }

    /**
     * @param Exception $e
     */
    protected function saveException(Exception $e)
    {
        if ($e instanceof Exception) {
            $exceptionWrap = new ExceptionWrap($e);
            $exceptionText = json_encode($exceptionWrap, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        else {
            $exceptionText = '"Error !"';
        }

        $this->jobman->status        = 'fail';
        $this->jobman->exception     = $exceptionText;
        $this->jobman->error_message = $e->getMessage();
        $this->jobman->failed_at     = date('c');
        $this->jobman->save();
    }

    /**
     * @return mixed
     */
    private function getJobman()
    {
        return JobmanEloquent::where('id', $this->jobmanId)
            ->first();
    }

}
