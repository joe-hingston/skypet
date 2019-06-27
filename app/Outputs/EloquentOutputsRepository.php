<?php


namespace App\Outputs;

use App\Output;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use phpDocumentor\Reflection\Types\Integer;


class EloquentOutputsRepository implements OutputsRepository
{
    private $search;


    public function __construct(Client $client) {
        $this->search = $client;
    }

    public function search(string $query = ""): Collection
    {
        $items = $this->searchOnElasticsearch($query);

        return $this->buildCollection($items);
    }

    public function all($size, $start): Collection
    {


        $items = $this->returnallElasticsearch($size, $start);

        return $this->buildCollection($items);
    }

    private function returnallElasticsearch($size, $start): array
    {


        $instance = new Output;

        $items = $this->search->search([
            'index' => $instance->getSearchIndex(),
            'type' => $instance->getSearchType(),
            'body' => [
                'from' => $start,
                'size' =>$size,
                ],
        ]);

        return $items;
    }


    private function searchOnElasticsearch(string $query): array
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
