<?php

namespace App\Jobs;

use App\Output;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use SimpleXMLElement;
use Illuminate\Support\Facades\Event;
use Exception;

class ProcessAbstract implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 120;
    protected $doi;
    protected $err;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($doi)
    {

        $this->onQueue('abstracts');
        $this->onConnection('redis');
        $this->doi = $doi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Redis::throttle('key')->allow(1)->every(5)->then(function () {
            // Job logic...

            $xml_str = file_get_contents($this->getAPIUrl()); //grab the contents
            $xml = new SimpleXMLElement($xml_str); //convert to SimpleXML
            $xmlid = $xml->xpath('IdList/Id');

            //TODO This is a hash
            $xmlid = strval($xmlid[0]);



            if (isset($xml->ErrorList) == false) {
                $curl = curl_init();
                $field = array('db' => 'pubmed', 'retmode' => 'text', 'rettype' => 'abstract', 'id' => $xmlid);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?".http_build_query($field),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLINFO_HEADER_OUT => true,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                ));

                $response = curl_exec($curl);
                $this->err = curl_error($curl);

                Output::where('doi', $this->doi)->update(['abstract' => $response]);
                curl_close($curl);

            }
            sleep(1);

            Event::fire('abstract.created', $this->doi);

        }, function () {
            // Could not obtain lock...
            return $this->release(10);
        });
    }


    public function getAPIUrl (){
      return  'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&WebEnv=1&usehistory=y&term='.$this->doi;
    }

    public function failed(Exception $exception)
    {
        $errormsg = "Failed Abstract with DOI: " . $this->doi;
        Log::error($errormsg);
    }

}
