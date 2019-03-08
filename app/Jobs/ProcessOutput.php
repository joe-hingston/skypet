<?php

namespace App\Jobs;

use App\Output;
use DOMDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Redis;
use SimpleXMLElement;

class ProcessOutput implements ShouldQueue
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
            // Job logic...

            $url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&WebEnv=1&usehistory=y&term='.$this->doi->doi;

            log::error($this->doi->id. ' Url is '. $url);
            $xml_str = file_get_contents($url); //grab the contents
            $xml = new SimpleXMLElement($xml_str); //convert to SimpleXML

            if(isset($xml->ErrorList)==false)
            {

                //build the query
                $fetchPub = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&retmode=xml&rettype=abstract&query_key='.implode($xml->xpath('QueryKey')).'&WebEnv='.implode($xml->xpath('WebEnv'));
                log::critical($fetchPub);

                $xml = simplexml_load_file($fetchPub);


                //Load into a DomDocument and print out
                $dom = new DOMDocument( '1.0', 'utf-8' );
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML( $xml->asXML() );
                $marker = $dom->getElementsByTagName( 'Abstract' );


                for ( $i = $marker->length - 1; $i >= 0; $i-- ) {
                    Output::where('doi', $this->doi->doi)->update(['abstract'=>$marker->item( $i )->textContent]);
                    Log::error($marker->item( $i )->textContent);

                }



            } else {
                Log::error("Error locating DOI on Pubmed. Is this located on another site? DOI:" . $this->doi->doi);
            }

        }, function () {
            // Could not obtain lock...

            return $this->release(10);
        });
    }
}
