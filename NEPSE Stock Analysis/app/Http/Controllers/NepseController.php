<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nepse;
use App\Stock;
use App\UrlDom;

class NepseController extends Controller
{

    public function getCurl($url) {
        $ch = curl_init();  // Initialising cURL session
        // Setting cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $results = curl_exec($ch);  // Executing cURL session
        curl_close($ch); // Closing cURL session
    
        $xmlPageDom = new \DOMDocument(); 
        @$xmlPageDom->loadHTML($results); 
        $xmlPageXPath = new \DOMXPath($xmlPageDom);

        // echo $results;
    
        return $xmlPageXPath;
    }

    public function calculate() {
        set_time_limit(1000);
        $nepse = Nepse::orderBy('date','asc')->get();

        $sm = 10;
        $md = 25;

        foreach($nepse as $np) {
            $candle['head'] = "";
            $candle['body'] = "";
            $candle['tail'] = "";
            $color = "";
            $position = "";

            $candle['body'] = $np->close - $np->open;

            if($candle['body'] >= 0) {
                $color = "g";
                $candle['head'] = $np->high - $np->close;
                $candle['tail'] = $np->open - $np->low;
            }

            else {
                $color = "r";
                $candle['head'] = $np->high - $np->open;
                $candle['tail'] = $np->close - $np->low;
            }

            $cdl = [];

            foreach($candle as $key => $cn) {
                $cn = abs(round($cn));
                if($cn < 2) $cdl[$key] = "n";
                elseif($cn <= $sm) $cdl[$key] = "s";
                elseif($cn <= $md) $cdl[$key] = "m";
                else $cdl[$key] = "l";
            }

            if(isset($prev_1)) {
                $prev = $prev_1;
                $position = "";
                $op = $np->open;
                if($op > $prev->high) $position = "u";
                elseif($op < $prev->low) $position = "d";
                else {
                    if($prev->color == "g") {
                        if($op > $prev->close) $position = "h";
                        elseif($op > $prev->open) $position = "b";
                        else $position = "t";
                    }
                    else {
                        if($op > $prev->open) $position = "h";
                        elseif($op > $prev->close) $position = "b";
                        else $position = "t";
                    }
                }
            }

            else {
                $prev_1 = $np;
                $position = "b";
            }

            if(abs($candle['body']) < 3) {
                $color = "b";
            }

            $code = $cdl['head'].$cdl['body'].$cdl['tail'].$color.$position;

            $np->code = $code;
            if(isset($prev_2)) {
                $np['prev_1'] = $prev_1->code;
                $np['prev_2'] = $prev_2->code;
            }

            $np->update();

            $prev_2 = $prev_1;
            $prev_1 = $np;
        }

        $nepse = Nepse::orderBy('date','desc')->get();

        foreach($nepse as $np) {
            if(isset($next_2)) {
                $np['next_1'] = $next_1->code;
                $np['next_2'] = $next_2->code;
                $next_2 = $next_1;
                $next_1 = $np;
                $np->update();
            }
            else {
                $next_1 = $np;
                $next_2 = $np;
            }

        }
    }

    public function find(Request $request) {
        if(isset($request->date)) {
            $np = Nepse::where('date',$request->date)->first();
            $code = $np->code;
        }

        else $code = $request->code;
        
        if($code) {
            $l3_results = Nepse::where('code',$code)->get();

            return view('nepse.result', [
                "l3" => $l3_results,
            ]);
        }

        else return redirect('/nepse');
    }

    public function update() {
        $latest_candles = Nepse::where('date',date('Y-m-d'));
        if($latest_candles->doesntExist()) {        
            $page = $this->getCurl("https://merolagani.com/LatestMarket.aspx");

            $tr = $page->query('//td');

            $len =  $tr->length;

            echo $len;

            echo $results;

            for($i=0; $i<250 ;$i++) {
                $symbol = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$i.']/td[1]/a');
                $close = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$i.']/td[2]');
                $change = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$i.']/td[3]');
                $high = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$i.']/td[4]');
                $low = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$i.']/td[5]');
                $open = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$i.']/td[6]');
                $qty = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$i.']/td[7]');

                //*[@id="ctl00_ContentPlaceHolder1_divData"]/div[3]/table/tbody/tr[2]/td[2]

                if(isset($symbol->item(0)->nodeValue)) {       
                    echo $symbol->item(0)->nodeValue;
                    $candle = new Nepse;
                    $candle['date'] = date('Y-m-d');
                    $candle['day'] = date('l',strtotime($candle['date']));
                
                    $candle['symbol'] = $symbol->item(0)->nodeValue;
                    $candle['open'] = floatval(str_replace(",","",$open->item(0)->nodeValue));
                    $candle['high'] = floatval(str_replace(",","",$high->item(0)->nodeValue));
                    $candle['low'] = floatval(str_replace(",","",$low->item(0)->nodeValue));
                    $candle['close'] = floatval(str_replace(",","",$close->item(0)->nodeValue));
                    $candle['change'] = floatval(str_replace(",","",$change->item(0)->nodeValue));
                    $candle['qty'] = $qty->item(0)->nodeValue;
                    $candle['code'] = 'hhllw';
                    $candle->save();
                    

                    // return redirect('/nepse');
                }
            }
        }            
    }

    public function getHotScrips() {
        $page = $this->getCurl("https://merolagani.com/MarketSummary.aspx?type=turnovers");

        $td = $page->query('//*[@id="ctl00_ContentPlaceHolder1_tblSummary"]/tbody/tr');

        echo $td->length;

        $scrips = [];
        for($i=3;$i<5;$i++) {
            $symbol = $page->query('//*[@id="ctl00_ContentPlaceHolder1_tblSummary"]/tbody/tr[2]/td[1]/a');
            if(false) {
                echo "hello";
                $symbol = $page->query('//*[@id="ctl00_ContentPlaceHolder1_tblSummary"]/tbody/tr['.$i.']/td[1]/a');
                $turnover = $page->query('//*[@id="ctl00_ContentPlaceHolder1_tblSummary"]/tbody/tr['.$i.']/td[2]')->item(0)->nodeValue;
                $turnover = str_replace(",",$turnover) + 0;
                $volume = $page->query('//*[@id="ctl00_ContentPlaceHolder1_tblSummary"]/tbody/tr['.$i.']/td[8]')->item(0)->nodeValue;
                $volume = str_replace(",",$volume) + 0;
                $scrips[] = ["symbol" => $symbol,
                            "turnover" => $turnover,
                            "volume" => $volume];
            }
        }
        $temp = $scrips;
        
        usort($temp, function($a,$b) {
            return $a->volume - $b->volume;
        });

        print_r($scrips);
        print_r($temp);
    }

    public function gannSquares() {
        $page = new UrlDom();
        $page->load("https://merolagani.com/LatestMarket.aspx");
        $live_stocks = $page->get_table_rows('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr');

        $odd_squares = [];
        $even_squares = [];
        $selectedList = [];
        $goodClose = [];

        // for($i=11; $i<50; $i = $i+2) {
        //     $odd_squares[] = pow($i,2);
        // }

        // for($i=10; $i<50; $i = $i+2) {
        //     $even_squares[] = pow($i,2);
        // }
        
        foreach ($live_stocks as $stock) {
            $sym = $stock[0];
            $ltp = floatval($stock[1]);
            $high = floatval($stock[3]);
            $low = floatval($stock[4]);
            $open = floatval($stock[5]);

            $squareRoot = floor(sqrt($ltp));
            $squareRoot = $squareRoot%2==0 ? $squareRoot + 1: $squareRoot;


            for($i=$squareRoot; $i<$squareRoot+3; $i= $i+2) {
                $squareNum = pow($i, 2);
                if($high+2 > $squareNum && $low-2 < $squareNum) {
                    $selectedList[$squareNum][] = [$sym, $low, $high, $ltp];
                    echo $squareNum." ".$sym.$ltp."<br>";
                    if($ltp <= $squareNum + 2 && $ltp >= $squareNum - 2) {
                        $goodClose[] = [$squareNum, $sym, $low, $high, $ltp];
                    }
                    break;
                }
            }
        }

        dd($goodClose);
    }
}
