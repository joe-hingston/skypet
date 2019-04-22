<?php

namespace App\Jobs;

use App\Output;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Redis;
use SimpleXMLElement;

class ProcessEmptyAbstracts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('abstracts');
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Redis::throttle('key')->allow(1)->every(5)->then(function () {
            $outputs = Output::where('abstract', null)->get();

            foreach ($outputs as $item) {

                $url = $this->getAPIUrl($item->doi);
                $xml_str = file_get_contents($url); //grab the contents
                $xml = new SimpleXMLElement($xml_str); //convert to SimpleXML

                // removes all the actual ones without any abstracts, content pages etc
                if (!empty($xml->ErrorList)) {
                    $outputs->forget($item->id);
                } // hasnt thrown an error so why is it not putting the abstract?
                else {
                    $xmlid = $xml->xpath('IdList/Id');
                    $xmlid = strval($xmlid[0]);
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
                    $err = curl_error($curl);

                    Output::where('doi', $item->doi)->update(['abstract' => $response]);

                }
                sleep(1);


            }

        }, function () {
            return $this->release(10);

        });
        //
    }
    public function getAPIUrl ($doi){
        return  'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&WebEnv=1&usehistory=y&term='.$doi;
    }

}
