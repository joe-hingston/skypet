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

    public function outputreferences()
    {
        return $this->hasMany(OutputReferences::class);
    }
}
