<?php


set_time_limit(0);
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Jobs\ProcessJournal;
use App\Output;


Route::get('journalseed', function () {

    //Add new Journal to the que
    ProcessJournal::dispatch('0891-6640')->onConnection('redis')->onQueue('journals');

});

Route::get('emptyabstracts', function () {

    //Job to search for the empty abstracts and try to update them
    $outputs = Output::where('abstract', null)->get();
    foreach ($outputs as $item) {

        $url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&WebEnv=1&usehistory=y&term='.$item->doi;
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





});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

