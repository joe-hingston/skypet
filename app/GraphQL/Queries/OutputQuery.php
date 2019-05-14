<?php


namespace App\GraphQL\Queries;


use App\Output;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class OutputQuery extends Query
{

    protected $attributes = [
        'name' => 'output',
    ];

    public function type()
    {
        return GraphQL::type('Output');
    }




    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required'],

            ],
        ];
    }
    public function resolve($root, $args)
    {
        return Output::findOrFail($args['id']);
    }

}
