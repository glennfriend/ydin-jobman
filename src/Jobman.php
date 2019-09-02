<?php
declare(strict_types = 1);
namespace Jobman;

use Jobman\Dispatch\JobDispatch;

/**
 *
 */
class Jobman
{
    /**
     * @param string $type
     * @param array $data
     * @return bool
     */
    public function push(string $type, array $data=[])
    {
        $dispatch = app(JobDispatch::class);
        return $dispatch->create($type, $data);
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------

}
