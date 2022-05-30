<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Jobs\ScrapeStocks;

class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if($request->p != "@samalamadumalama..") {
            return view('welcome');
        }

        $last_date = date('Y-m-d');

        if($request->action == "update") {
            $this->update();
        }

        // if($request->action == "reset") {
        //     ScrapeStocks::dispatchAfterResponse();
        //     return "Reset In Progress.";
        // }

        $latest_stocks = Stock::where('index_1','>',0)->orderBy('index_1','asc')->get();

        $prev_date_stock = Stock::latest()->whereDate('created_at','<',$last_date)->first();
        $last_date = date('Y-m-d',strtotime($prev_date_stock['created_at']));          
        $latest_stocks = Stock::where('index_1','>',0)->orderBy('index_1','asc')->get();

        $top_sector_stocks = [];
        $sector = [];

        function setVal($a) {
            if(isset($a)) {
                return true;
            }
            else return false;
        }

        foreach($latest_stocks as $lts) {
            $sect = $lts->sector;
            if(!isset($sector[$sect])) {
                $sector[$sect]['ltp'] = [];
                $sector[$sect]['52_high'] = [];
                $sector[$sect]['52_low'] = [];
                $sector[$sect]['yield'] = [];
                $sector[$sect]['pe_ratio'] = [];
                $sector[$sect]['eps'] = [];
                $sector[$sect]['book_value'] = [];
                $sector[$sect]['pbv_ratio'] = [];
            }

            $sector[$sect]['ltp'][] = $lts['ltp'];
            $sector[$sect]['52_high'][] = $lts['52_high'];
            $sector[$sect]['52_low'][] = $lts['52_low'];
            $sector[$sect]['yield'][] = $lts['yield'];
            $sector[$sect]['pe_ratio'][] = $lts['pe_ratio'];
            $sector[$sect]['eps'][] = $lts['eps'];
            $sector[$sect]['book_value'][] = $lts['book_value'];
            $sector[$sect]['pbv_ratio'][] = $lts['pbv_ratio'];
            
        }
        
        $sector_avg = [];
        // foreach($sector as $s => $v) {
        //     $sum = 0;
        //     $count = count($v);
        //     foreach($v as $i) {
        //         $sum += $i;
        //     }

        //     $sector_avg[$s] = round($sum/$count,2);
        // }


        foreach($sector as $s => $v) {
            foreach($v as $n => $i) {
                sort($i);
                $sector_avg[$s][$n] = $i[floor(count($i)/2)];
            }
            
            $top_sector_stocks[$s] = Stock::where('sector',$s)->where('index_1','>',0)->orderBy('index_1','asc')->get();
        }

        $gi = ["Hydro Power" => 80,
        "Commercial Banks" => 40,
        "Microfinance" => 200,
        "Finance" => 40,
        "Others" => 40,
        "Non-Life Insurance" => 250,
        "Manufacturing And Processing" => 40,
        "Development Bank Limited" => 50,
        "Investment" => 40,
        "Life Insurance" => 250,
        "Tradings" => 40,
        "Hotels And Tourism" => 30
    ];

        foreach($latest_stocks as $st) {
            $st['graham_price'] = ceil(sqrt($st['book_value']*$st['eps']*$gi[$st->sector]));
            if(is_nan($st['graham_price'])) {
                $st['graham_price'] = 1;
            }

            $st['ltp-sa'] = round(($st->ltp - $sector_avg[$st->sector]['ltp'])/$sector_avg[$st->sector]['ltp'],2)*100;
        }

        $ls = $latest_stocks->first();

        return view('home',[
        'last_date' => date('Y-m-d    H:i',strtotime('+5 hours 45 minutes', strtotime($ls['updated_at']))),
        'sector_avg' => $sector_avg,
        'latest_stocks' => $latest_stocks,
        'top_sector_stocks' => $top_sector_stocks
        ]);
    }

    public function update() {

        $url = "https://merolagani.com/LatestMarket.aspx";
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
    
        $page = $xmlPageXPath;
    
        $stocks = [];
        for($k=1;$k<=216;$k++) {
            $sym = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$k.']/td[1]');
            $ltp = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$k.']/td[2]');
            if(isset($sym->item(0)->nodeValue)) {
                $stocks[$sym->item(0)->nodeValue] = $ltp->item(0)->nodeValue; 
            }
            
        }

        $latest_stocks = Stock::all();

        foreach($latest_stocks as $ls) {
            $sym = $ls->symbol;
            if(isset($stocks[$sym])) {
                $ltp = floatval(str_replace(',','',$stocks[$sym]));
                $ls['ltp'] = $ltp;
                $ls['52_high'] = $ls['52_high'] > $ltp ? $ls['52_high'] : $ltp;
                $ls['pe_ratio'] = $ls['eps'] ? $ltp/$ls['eps'] : $ls['pe_ratio'];
                $ls['pbv_ratio'] = $ls['book_value'] ? $ltp/$ls['book_value'] : $ls['pbv_ratio'];
                $sd = ($ls['52_high']-$ls['ltp'])/$ls['ltp']*10;
                $sd = $sd ? $sd : 0.01;
                $ls['index_1'] = round(10*$ls['pe_ratio']*$ls['pbv_ratio']/(sqrt($ls['yield'])*$sd),2);
                // $ls['index_1'] = $ls['pe_ratio']*$ls['pbv_ratio']/($ls['yield']+0.1)*100;
                $nt_pe = $ls['pe_ratio'] > 0 ? $ls['pe_ratio'] : 100;
                $ls['index_2'] = round($nt_pe*$ls['pbv_ratio']/$sd,2);
                if(abs($ls['index_1'])>999999) {
                    $ls['index_1'] = 0;
                }
                if(abs($ls['index_2'])>999999) {
                    $ls['index_2'] = 0;
                }
            }
            $ls->update();
        }
    }

    public function doji() {

        $url = "https://merolagani.com/LatestMarket.aspx";
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
    
        $page = $xmlPageXPath;
    
        $doji = [];
        for($k=1;$k<=216;$k++) {
            $sym = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$k.']/td[1]');
            $ltp = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$k.']/td[2]');
            $high = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$k.']/td[5]');
            $low = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$k.']/td[6]');
            $open = $page->query('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr['.$k.']/td[7]');
            if(isset($sym->item(0)->nodeValue)) {
                $ltp = intval($ltp->item(0)->nodeValue);
                $open = intval($open->item(0)->nodeValue);
                $high = intval($high->item(0)->nodeValue);
                $low = intval($low->item(0)->nodeValue);
                $diff = $ltp - $open;
                if(abs($diff) < 4) {
                    if($ltp > $open) {
                        $hd = round(($high - $ltp));
                        $ld = round(($open - $low));
                        $hl = round(($high - $low));
                    }
                    else {
                        $hd = round(($high - $open));
                        $ld = round(($ltp - $low));
                        $hl = round(($high - $low));
                    }
                    
                    if($hl && abs($hd - $ld) > 3 && $diff) {
                        $rdc = abs($hd) < 2 xor abs($ld) < 2 ? 3 : 1;
                        
                        $ind = abs(round((abs($hd-$ld)-abs($diff))/($ltp*$rdc)*10000));
                        $doji[] = [$sym->item(0)->nodeValue,$hd,$diff,$ld,$hd-$ld,$ind];
                    }  
                }
            }           
        }

        usort($doji, function($a,$b) {
            return $a[5] - $b[5];
        });

        return view('nepse.doji',['doji' => $doji]);
        }

        public function graham() {
            $stocks = Stock::all();
            foreach($stocks as $st) {
                $st->sector = trim($st->sector);
                $st->update();
            }
        }
    
}
