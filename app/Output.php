<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class Output extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

    }


    public function journal()
    {
        return $this->belongsTo(Journal);
    }

    public function outputreferences()
    {
        return $this->hasMany(OutputReferences::class);
    }
}
