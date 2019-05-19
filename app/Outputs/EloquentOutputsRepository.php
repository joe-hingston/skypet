<?php


namespace App\Outputs;

use App\Output;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;



class EloquentOutputsRepository implements OutputsRepository
{
    private $search;


    public function __construct(Client $client) {
        $this->search = $client;
    }

    public function search(string $query = "", string $limit = ""): Collection
    {
        $items = $this->searchOnElasticsearch($query, $limit);

        return $this->buildCollection($items);
    }

//    public function searchDOI(string $query = "", string $limit = ""): Collection
//    {
//        $items = $this->searchOnElasticsearchDOI($query, $limit);
//
//        return $this->buildCollection($items);
//    }





    private function searchOnElasticsearch(string $query, string $limit): array
    {
        $instance = new Output;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title', 'abstract', 'doi'],
                        'query' => $query,
                    ],
                ],
                'size' => $limit
            ],
        ]);

        return $items;
    }

    private function searchOnElasticsearchDOI(string $query, string $limit): array
    {
        $instance = new Output;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                'query' => [
                    'match' => [
                        'doi' => $query,
                    ],
                ],
                'size' => $limit
            ],
        ]);

        return $items;
    }



    private function buildCollection(array $items): Collection
    {
        /**
         * The data comes in a structure like this:
         *
         * [
         *      'hits' => [
         *          'hits' => [
         *              [ '_source' => 1 ],
         *              [ '_source' => 2 ],
         *          ]
         *      ]
         * ]
         *
         * And we only care about the _source of the documents.
         */
        $hits = array_pluck($items['hits']['hits'], '_source') ?: [];

        $sources = array_map(function ($source) {
            // The hydrate method will try to decode this
            // field but ES gives us an array already.
          //  $source['tags'] = json_encode($source['tags']);
            return $source;
        }, $hits);

        // We have to convert the results array into Eloquent Models.
        return  Output::hydrate($sources);
    }
}
