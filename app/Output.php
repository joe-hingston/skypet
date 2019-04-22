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

        static::updated(function ($output) {
            Event::fire('output.updated', $output);
        });
        static::created(function ($output) {
            Event::fire('output.created', $output);
        });
        static::deleted(function ($output) {
            Event::fire('output.deleted', $output);
        });
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
