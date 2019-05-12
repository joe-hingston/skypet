<?php


namespace App;


use hamburgscleanest\LaravelGuzzleThrottle\Facades\LaravelGuzzleThrottle;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class AbstractFetcher
{
    public $apikey = '9edead42a0bb081a0a6e8db1cc5c4d6e7809';
    //TODO move API keys to the .env?
    protected $output;

    public function __construct(Output $output)
    {
        $this->doi = $output->doi;
        $this->output = $output;
    }

    public function fetch()
    {

        //TODO check the response codes
        try {
            $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://eutils.ncbi.nlm.nih.gov/']);
            $res = $client->get($this->getAPIUrl());
            $xmlid = simplexml_load_string($res->getBody());
            $xmlid = $xmlid->IdList->Id->__toString();
            $client = LaravelGuzzleThrottle::client(['base_uri' => 'https://eutils.ncbi.nlm.nih.gov/']);
            $res = $client->get($this->getOutputUrl($xmlid));
            $xmlid = simplexml_load_string($res->getBody());

            if (isset($xmlid->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText)) {
                $stringpieces = [];
                foreach ($xmlid->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText as $text) {
                    $stringpieces[] = (string)$text;
                }
                $abstract = implode(' ', $stringpieces);

                Output::where('doi', $this->doi)->update(['abstract' => $abstract]);
            } else {
                die();
            }

        } catch (Exception $e) {
            Log::alert('Error with processing abstract');
            Log::alert('Output ID ' . $this->output->id);
            Log::alert('Error Message' . $e->getMessage());
            Log::alert('API Url ' . $this->getAPIUrl());
            Log::alert('Output Url ' . $this->getOutputUrl('test'));
        };


    }

    public function getAPIUrl()
    {
        return 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&WebEnv=1&usehistory=y&term=' . $this->doi . '$api_key=' . $this->apikey;
    }

    public function getOutputUrl($xmid)
    {
        $field = array('db' => 'pubmed', 'retmode' => 'xml', 'rettype' => 'abstract', 'id' => $xmid);
        return "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?" . http_build_query($field);
    }

}
