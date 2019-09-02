<?php

namespace Jobman\Entities\Eloquent;

use Illuminate\Database\Eloquent\Model;

class JobmanEloquent extends Model
{
    protected $table = 'jobmans';
    public $timestamps = false;
}
