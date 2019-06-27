<?php

namespace App\Outputs;

use Illuminate\Database\Eloquent\Collection;
use phpDocumentor\Reflection\Types\Integer;


interface OutputsRepository
{
    public function search(string $query = ""): Collection;
    public function all($size, $start): Collection;
}


