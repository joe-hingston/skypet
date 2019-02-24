<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    protected $guarded = [];

    public function journal()
    {
        return $this->belongsTo(Journal);
    }
}
