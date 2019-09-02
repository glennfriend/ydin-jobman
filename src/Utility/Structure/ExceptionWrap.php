<?php
namespace Jobman\Utility\Structure;

use Exception;

/**
 * Exception wrapper
 * 整理部份資訊, 以方便 簡示 & 寫入 log
 *
 * @version 0.1.0
 * @package Jobman\Utility\Structure
 */
class ExceptionWrap
{

    /**
     *
     */
    public function __construct(Exception $exception)
    {
        $this->trace_files = [];
        $this->trace_calls = [];
        $this->trace_count = count($exception->getTrace());
        $this->file = $exception->getFile() ?? null;
        $this->code = $exception->getCode();
        $this->message = $exception->getMessage();

        $i = 0;
        foreach ($exception->getTrace() as $trace) {

            $file = '';
            if (isset($trace['file']) && isset($trace['line'])) {
                $file .= $trace['file'] . ' :' . $trace['line'];
            }

            $call = '';
            if (isset($trace['class']) && isset($trace['type'])) {
                $call .= $trace['class'] . ' ' . $trace['type'];
            }
            if (isset($trace['function'])) {
                $call .= ' ' . $trace['function'];
            }


            $this->trace_files[] = $file;
            $this->trace_calls[] = trim($call);

            if (++$i >= 10) {
                break;
            }
        }

        /*
            if ($exception instanceof Exception) {
                //
            }
        */
    }

}
