<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class Journal extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

    }

    public function outputs() {

        return $this->hasMany(Output::class);

    }
}
