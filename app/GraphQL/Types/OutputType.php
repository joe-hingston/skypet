<?php
namespace App\GraphQL\Types;
use App\Output;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OutputType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Output',
        'description' => 'Details about each Output',
        'model' => Output::class
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of the Output',
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Title of the Output',
            ],
            'abstract' => [
                'type' => Type::string(),
                'description' => 'Abstract of the Output',
            ],


        ];
    }

}
