<?php

namespace App;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Model;


class Output extends Model
{
    use Searchable;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

    }


    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

}
