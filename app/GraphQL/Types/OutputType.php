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
            'doi' => [
                'type' => Type::string(),
                'description' => 'DOI of the output',
            ],
            'publisher' => [
                'type' => Type::string(),
                'description' => 'Publisher of the output',
            ],
            'issn' => [
                'type' => Type::string(),
                'description' => 'ISSN for the Output',
            ],
            'eissn' => [
                'type' => Type::string(),
                'description' => 'EISSN for the Output',
            ],
            'language' => [
                'type' => Type::string(),
                'description' => 'Language of the Output',
            ],
            'reference_count' => [
                'type' => Type::string(),
                'description' => 'reference count of the Output',
            ],
            'page' => [
                'type' => Type::string(),
                'description' => 'Page of the Output',
            ],
            'url' => [
                'type' => Type::string(),
                'description' => 'Url of the Output',
            ],
            'miningurl' => [
                'type' => Type::string(),
                'description' => 'MiningUrl of the Output',
            ],
            'is_referenced_by' => [
                'type' => Type::string(),
                'description' => 'How many is referenced Output',
            ],
            'source' => [
                'type' => Type::string(),
                'description' => 'Source of the Output',
            ],
            'prefix' => [
                 'type' => Type::string(),
                'description' => 'Prefix of the Output',
            ],
            'volume' => [
                'type' => Type::string(),
                'description' => 'Volume of the Output',
            ],
            'member' => [
                'type' => Type::string(),
                'description' => 'Volume of the Output',
            ],
            'score' => [
                'type' => Type::string(),
                'description' => 'Volume of the Output',
            ],
            'issue' => [
                'type' => Type::string(),
                'description' => 'Volume of the Output',
            ],

            //TODO change the fields so they don't contain dashes - Cannot use hyphens in GraphQL

        ];

        // protected function resolveCreatedAtField($root, $args)
        //      {
        //        return (string) $root->created_at;
        //      }
    }

}
