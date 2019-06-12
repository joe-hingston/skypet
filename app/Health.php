<?php

namespace App;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Model;

class Health extends Model
{
    use Searchable;
    protected $guarded = [];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
    //
}
