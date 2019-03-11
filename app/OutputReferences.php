<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputReferences extends Model
{
    protected $guarded = [];

    public function output()
    {
        return $this->belongsTo(Output::class);
    }
}
