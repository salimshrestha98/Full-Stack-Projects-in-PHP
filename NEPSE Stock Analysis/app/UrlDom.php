<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlDom extends Model
{
    protected $fillable = [
        'url',
        'raw',
        'page'
    ];

    public function load($url) {

        $this->url = $url;
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

        $ch = curl_init();  // Initialising cURL session
        // Setting cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_URL, $this->url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);

        $results = curl_exec($ch);  // Executing cURL session
        curl_close($ch); // Closing cURL session

        $this->raw = $results;
    
        $xmlPageDom = new \DOMDocument(); 
        @$xmlPageDom->loadHTML($results); 
        $xmlPageXPath = new \DOMXPath($xmlPageDom);
    
        $this->page = $xmlPageXPath;
    }

    public function get_string($query) {
        return trim($this->page->query($query)->item(0)->nodeValue);
    }

    public function get_response() {
        return $this->raw;
    }

    public function get_float($query) {
        $val = $this->get_string($query);
        return floatval(str_replace(',', '', $val));
    }



    /*
        This function returns the specified table data as an array.

        Parameters:
            $query ==> The XPath query that points to the table rows. eg. "*table/tbody/tr"
            $ri ==> The starting row index to fetch data

    */

    public function get_table_rows($query, $ri = 0) {

        $arr = [];
        $result = $this->page->query($query);

        if( !is_null($result) ) {
            foreach( $result as $row) {
                $q1 = $query.'['.$ri++.']/td';

                $row_data = $this->page->query($q1);

                $rd = [];
                foreach ($row_data as $td) {
                    if( is_a($td, 'DOMElement'))
                        $rd[] =  $td->nodeValue;
                }
                
                if( !empty($rd) )
                    $arr[] = $rd;
            }
        }

        return $arr;
    }
}
