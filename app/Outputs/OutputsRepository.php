<?php

namespace App\Outputs;

use Illuminate\Database\Eloquent\Collection;


interface OutputsRepository
{
    public function search(string $query = "", string $limit = ""): Collection;
}


