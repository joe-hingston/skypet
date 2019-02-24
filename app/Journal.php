<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $guarded = [];

    public function outputs() {

        return $this->hasMany(Output::class);

    }
}
