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

        static::updated(function ($journal) {
            Event::fire('journal.updated', $journal);
        });
        static::created(function ($journal) {
            Event::fire('journal.created', $journal);
        });
        static::deleted(function ($journal) {
            Event::fire('journal.deleted', $journal);
        });
    }

    public function outputs() {

        return $this->hasMany(Output::class);

    }
}
