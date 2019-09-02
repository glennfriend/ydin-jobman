<?php
declare(strict_types = 1);
namespace Jobman\Dispatch;

use Exception;
use DB;
use Log;
use Jobman\Entities\Eloquent\JobmanEloquent;

/**
 *
 */
class JobDispatch
{

    public function __construct(JobmanEloquent $jobmanEloquent)
    {
        $this->jobmanEloquent = $jobmanEloquent;
    }

    /**
     * @param string $type
     * @param array $data
     * @return int|null
     */
    public function create(string $type, array $data): ?int
    {
        $attribs = $data;
        $jobmanId = (int) $this->buildJobman($type, $attribs);

        $matchJob = $this->getJobByType($type);
        if (! $matchJob) {
            $fullClassName = get_called_class();
            Log::debug("{$fullClassName} fail: type not found, jobman={$jobmanId}");
            return null;
        }

        $className = $matchJob['class'];
        $className::dispatch($jobmanId);

        return $jobmanId;
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------

    /**
     * @param string $type
     * @param array $attribs
     * @return string
     * @throws Exception
     */
    protected function buildJobman(string $type, array $attribs)
    {
        $this->jobmanEloquent->attribs      = json_encode($attribs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $this->jobmanEloquent->type         = $type;
        $this->jobmanEloquent->created_at   = date('c');
        if (! $this->jobmanEloquent->save()) {
            throw new Exception('Jobman create fail !');
        }

        $jobmanId = DB::getPdo()->lastInsertId();
        return $jobmanId;
    }

    /**
     * @param string $type
     * @return array|null
     */
    protected function getJobByType(string $type): ?array
    {
        $jobs = config('jobman.jobs');
        $matchJob = null;
        foreach ($jobs as $job) {
            $tag = $job['tag'];
            if ($type === $tag) {
                return $job;
            }
        }

        return null;
    }

}
