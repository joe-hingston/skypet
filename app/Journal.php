<?php

namespace App;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class Journal extends Model
{
    use Searchable;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

    }

    public function outputs() {

        return $this->hasMany(Output::class);

    }
}
