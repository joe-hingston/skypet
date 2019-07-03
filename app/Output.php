<?php

namespace App;

use App\Http\Requests\QueryFilter;
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


    public function scopeFilter($query,QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
