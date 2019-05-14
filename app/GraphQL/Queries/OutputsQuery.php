<?php


namespace App\GraphQL\Queries;

use App\Output;
use GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\Type;
class OutputsQuery extends Query
{
    protected $attributes = [
        'name' => 'outputs',
    ];
    public function type()
    {
        return Type::listOf(GraphQL::type('Output'));
    }
    public function resolve($root, $args)
    {
        return Output::all();
    }
}
