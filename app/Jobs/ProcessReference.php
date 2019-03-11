<?php

namespace App\Jobs;

use App\Journal;
use App\Output;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;

class ProcessReference implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $timeout = 120;
    protected $doi;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($doi)
    {
        $this->doi = $doi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Redis::throttle('key')->allow(3)->every(1)->then(function () {

            $this->setApiUri('https://api.crossref.org/works/' . $this->doi);
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $this->getApiUri());
            $this->output = json_decode($response->getBody())->message;

            // Job logic...

            //create journal entry if non made
            Journal::updateOrCreate(['issn' => $this->output->ISSN[0]], [
                'issn' => $this->output->ISSN[0],
                'eissn' => $this->output->ISSN[0]
            ]);


            //add Output

            Output::updateOrCreate(['doi' => $this->output->doi], [
                    'doi' => $this->output->DOI,
                    'reference_count' => $this->output->{'reference-count'},
                    'url' => $this->output->URL,
                    'title' => $this->output->title[0],
                    'issn' => $this->output->ISSN[0],
                    'publisher' => $this->output->publisher,
                    'language' => $this->output->language,
                    'is_referenced_by' => $this->output->{'is-referenced-by-count'}
                ]
            );

            //Process Abstract
            ProcessAbstract::dispatch($this->output, $this->output->DOI)->onConnection('redis'); //grab the abstract


        });
    }

    /**
     * @param $url
     */
    public function setApiUri($url)
    {
        $this->apiUri = $url;
    }

    /**
     * @return mixed
     */
    public function getApiUri()
    {
        return $this->apiUri;
    }

    public function buildUrl()
    {

        return $this->apiUri . http_build_query([
                'offset' => $this->offset,
                'rows' => $this->qty
            ]);
    }

    /**
     * @param $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }


    public function getTotal($total)
    {
        return $this->total;
    }

}
