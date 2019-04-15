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

Route::get('abstract', function () {
    $this->doi = "10.1111/jvim.12275";
    $url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&WebEnv=1&usehistory=y&term='.$this->doi;
    $xml_str = file_get_contents($url); //grab the contents
    $xml = new SimpleXMLElement($xml_str); //convert to SimpleXML
    $xmlid = $xml->xpath('IdList/Id');
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
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);


    }
});

Route::get('journalseed', function () {

    //Add new Journal to the que
    ProcessJournal::dispatch('0891-6640')->onConnection('redis')->onQueue('journals');



});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
