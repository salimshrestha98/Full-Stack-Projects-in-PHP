<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Stock;
use App\UrlDom;

use App\Jobs\ScrapeStocks;
use App\Jobs\UpdateStocks;


class StocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   

        if(
            DB::table('updates')->where('date', date('Y-m-d'))->doesntExist() &&
            ( date('w') < 5 ) &&
            ( date('H') < 11 || date('H') > 15 )        
        ) {
            DB::table('updates')->insert([
                'type' => 'update',
                'date' => date('Y-m-d')
            ]);

            UpdateStocks::dispatchAfterResponse();
        }

        if($request->action == "update") {
            UpdateStocks::dispatch();
        }

        if($request->action == "reset") {
            ScrapeStocks::dispatchAfterResponse();
            return "Reset In Progress.";
        }

        $stocks_all = Stock::all();
        $stocks = [];

        foreach($stocks_all as $stock) {
            $stocks[$stock->symbol] = $stock;
        }

        $latest_stocks = Stock::select('symbol')->where('pe_ratio','>',0)->where('pe_ratio','<',100)->where('index_1','>',0)->orderBy('index_1','asc')->get();

        // $latest_stocks = Stock::select('symbol')->where('index_1','>',0)->where('pe_ratio','>',0)->where('pe_ratio','<',50)->where('pbv_ratio','<',15)->orderBy('index_1','asc')->get();

        // print_r($latest_stocks);

        // $prev_date_stock = Stock::latest()->whereDate('created_at','<',$last_date)->first();
        // $last_date = date('Y-m-d',strtotime($prev_date_stock['created_at']));          
        // $latest_stocks = Stock::select('symbol')->where('index_1','>',0)->orderBy('index_1','asc')->get();

        $top_sector_stocks = [];
        $sector = [];

        function setVal($a) {
            if(isset($a)) {
                return true;
            }
            else return false;
        }

        foreach($latest_stocks as $lts) {
            $sect = $stocks[$lts['symbol']]['sector'];
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

            $sector[$sect]['ltp'][] = $stocks[$lts['symbol']]['ltp'];
            $sector[$sect]['52_high'][] = $stocks[$lts['symbol']]['52_high'];
            $sector[$sect]['52_low'][] = $stocks[$lts['symbol']]['52_low'];
            $sector[$sect]['yield'][] = $stocks[$lts['symbol']]['yield'];
            $sector[$sect]['pe_ratio'][] = $stocks[$lts['symbol']]['pe_ratio'];
            $sector[$sect]['eps'][] = $stocks[$lts['symbol']]['eps'];
            $sector[$sect]['book_value'][] = $stocks[$lts['symbol']]['book_value'];
            $sector[$sect]['pbv_ratio'][] = $stocks[$lts['symbol']]['pbv_ratio'];
            
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
        
        $sector_trim = [];


        foreach($sector as $s => $v) {
            foreach($v as $n => $i) {
                sort($i);
                $sector_avg[$s][$n] = $i[floor(count($i)/2)];
            }

            $sector_trim[$s] = trim(str_replace([" ","-","_"],"",$s));
            
            $top_sector_stocks[$s] = Stock::select('symbol')->where('sector',$s)->where('index_1','>',0)->orderBy('index_1','asc')->get();
        }


        $gi = ["Hydro Power" => 80,
        "Commercial Banks" => 40,
        "Microfinance" => 100,
        "Finance" => 50,
        "Others" => 50,
        "Non-Life Insurance" => 150,
        "Manufacturing And Processing" => 50,
        "Development Bank Limited" => 50,
        "Investment" => 50,
        "Life Insurance" => 150,
        "Tradings" => 50,
        "Hotels And Tourism" => 50
    ];

    $top_scr = [];

    $top_scr_count = $gi;

    foreach($top_scr_count as $key => $t) {
        $top_scr_count[$key] = 5;
        $top_scr[$key] = [];
    }

        

        foreach($latest_stocks as $st) {
            $stocks[$st['symbol']]['graham_price'] = ceil(sqrt($stocks[$st['symbol']]['book_value']*$stocks[$st['symbol']]['eps']*$gi[$stocks[$st['symbol']]['sector']]));
            if(is_nan($stocks[$st['symbol']]['graham_price']) || ($stocks[$st['symbol']]['graham_price']) < 1) {
                $stocks[$st['symbol']]['graham_price'] = 1;
            }

            $stocks[$st['symbol']]['ltp-sa'] = round(($stocks[$st['symbol']]['ltp'] - $sector_avg[$stocks[$st['symbol']]['sector']]['ltp'])/$sector_avg[$stocks[$st['symbol']]['sector']]['ltp'],2)*100;

            if(count($top_scr[$stocks[$st['symbol']]['sector']]) < 5) {
                $top_scr[$stocks[$st['symbol']]['sector']][] = $st['symbol'];
            }

            $stocks[$st['symbol']]['sp'] = round($stocks[$st['symbol']]['eps'] * $sector_avg[$stocks[$st->symbol]['sector']]['pe_ratio']);
            $stocks[$st['symbol']]['sp-ltp'] = round($stocks[$st['symbol']]['ltp']/$stocks[$st['symbol']]['sp']*100); 
        }

        $nepse_avg = [
            'pe_ratio' => Stock::where('pe_ratio', '>', 0)->where('pe_ratio', '<', 1000)->avg('pe_ratio'),
            'pbv_ratio' => Stock::where('pbv_ratio', '<', 50)->avg('pbv_ratio')
        ];


        $ls = $latest_stocks->first();


        // return view('stocks.home',[
        // 'stocks' => $stocks,
        // 'last_date' => DB::table('updates')->orderBy('id', 'desc')->first()->date,
        // 'sector_avg' => $sector_avg,
        // 'sector_trim' => $sector_trim,
        // 'latest_stocks' => $latest_stocks,
        // 'top_sector_stocks' => $top_sector_stocks,
        // 'top_scr' => $top_scr,
        // 'nepse_avg' => $nepse_avg,
        // 'top_gainers' => $this->get_weekly_gainers(20),
        // 'top_transactions' => $this->get_weekly_transactions(20),
        // 'top_intersections' => array_intersect_key( $this->get_weekly_gainers(30), $this->get_weekly_transactions(30) ),
        // 'top_losers' => array_intersect_key( $this->get_top_losers(100), $this->get_weekly_transactions(30) )
        // ]);

        return view('stocks.home',[
        'stocks' => $stocks,
        'last_date' => DB::table('updates')->orderBy('id', 'desc')->first()->date,
        'sector_avg' => $sector_avg,
        'sector_trim' => $sector_trim,
        'latest_stocks' => $latest_stocks,
        'top_sector_stocks' => $top_sector_stocks,
        'top_scr' => $top_scr,
        'nepse_avg' => $nepse_avg
        ]);
    }










    public function getSectorAvg() {
        $sector_avg = [];
        $sector_trim = [];
        $sector = [];

        $latest_stocks = Stock::where('pe_ratio','>',0)->where('pe_ratio','<',100)->where('index_1','>',0)->orderBy('index_1','asc')->get();

        foreach($latest_stocks as $lts) {
            $sect = $lts['sector'];
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

        foreach($sector as $s => $v) {
            foreach($v as $n => $i) {
                sort($i);
                $sector_avg[$s][$n] = $i[floor(count($i)/2)];
            }
        }

        return $sector_avg;
    }












    public function show($sym) {
        $stock = Stock::where('symbol',$sym)->first();
        $similar_stocks = Stock::where('sector', $stock['sector'])
                                ->where('index_1','>',0)
                                ->where('pe_ratio','>',0)
                                ->where('pbv_ratio','<',15)
                                ->orderBy('index_1','asc')
                                ->limit(20)
                                ->get();

        $backgrounds = [];
        $range = ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f'];
        foreach($similar_stocks as $s) {
            $color = "#";
            for($i=0; $i<6; $i++) {
                $color .= $range[rand(0,count($range)-1)];
            }
            $backgrounds[] = $color;
            $s->bg = $color;
        }
        
        return view('stocks.show', [
            'stock' => $stock,
            'similar_stocks' => $similar_stocks,
            'canvas_names' => ['yield','return','eps','pe_ratio','book_value','pbv_ratio'],
            'backgrounds' => $backgrounds
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
        // $sector_avg = $this->getSectorAvg();

        foreach($latest_stocks as $ls) {
            $sym = $ls->symbol;
            if(isset($stocks[$sym])) {
                $ltp = floatval(str_replace(',','',$stocks[$sym]));
                $ls['ltp'] = $ltp;
                $ls['52_high'] = $ls['52_high'] > $ltp ? $ls['52_high'] : $ltp;
                $ls['pe_ratio'] = $ls['eps'] ? $ltp/$ls['eps'] : $ls['pe_ratio'];
                $ls['pbv_ratio'] = $ls['book_value'] ? $ltp/$ls['book_value'] : $ls['pbv_ratio'];
                $sd = ($ls['52_high']-$ls['ltp'])/$ls['ltp'];
                $ls['yield'] = $ls['yield'] < 200 ? $ls['yield'] : 200;
                $sd = $sd ? $sd : 0.01;

                $ls['index_1'] = round(pow($ls['pe_ratio'], 2)*$ls['pbv_ratio']) / (1 + ($ls['yield'] + $ls['return']*10 ));

                // $ls['index_1'] = round((abs(pow($ls['pe_ratio'],2)*$ls['pbv_ratio'])+pow((($ls['graham_price']/$ls['ltp']*100) + 1),5))/sqrt($ls['yield'] + $ls['return']*10),2);

                // $ls['index_1'] = round(10*$ls['pe_ratio']*$ls['pbv_ratio']/(sqrt($ls['yield'])*$sd),2);
                // $ls['index_1'] = $ls['pe_ratio']*$ls['pbv_ratio']/($ls['yield']+0.1)*100;
                $nt_pe = $ls['pe_ratio'] > 0 ? $ls['pe_ratio'] : 100;
                $ls['index_2'] = round($nt_pe*$ls['pbv_ratio']/$sd,2);
                if(abs($ls['index_1'])>999999 || is_nan($ls['index_1'])) {
                    $ls['index_1'] = 0;
                }
                if(abs($ls['index_2'])>999999 || is_nan($ls['index_2'])) {
                    $ls['index_2'] = 0;
                }
            }
            $ls->update();
        }

        // $this->fetch_top_gainers();
        // $this->fetch_top_transactions();
        // $this->fetch_top_volume();
    }



    

    public function doji() {

        $page = new UrlDom();
        $page->load("https://merolagani.com/LatestMarket.aspx");
        $live_stocks = $page->get_table_rows('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr');
    
        $doji = [];
        
        foreach ($live_stocks as $stock) {
            $sym = $stock[0];
            $ltp = floatval($stock[1]);
            $high = floatval($stock[3]);
            $low = floatval($stock[4]);
            $open = floatval($stock[5]);

            $body = $ltp - $open;

            if(abs($body)/($high + 1 - $low) < 0.3) {
                if($ltp > $open) {
                    $head = round(($high - $ltp));
                    $tail = round(($open - $low));
                    $length = round(($high - $low));
                }
                else {
                    $head = round(($high - $open));
                    $tail = round(($ltp - $low));
                    $length = round(($high - $low));
                }
                
                if($length && abs($head - $tail) > 3 && $body > 0 && $body < 3 && $head && $tail) {
                    $rdc = abs($head) < 2 xor abs($tail) < 2 ? 3 : 1;
                    
                    // $ind = abs(round((abs($head-$tail)-abs($body))/($ltp*$rdc)*10000));
                    $doji[] = [$sym,
                                round($head/$length*100),
                                round(abs($body)/$length*100),
                                round($tail/$length*100),
                            ];
                }  
            }           
        }

        usort($doji, function($a,$b) {
            return $a[2] - $b[2];
        });

        // dd($doji[1][1] > 5);

        return view('nepse.doji',['doji' => $doji]);
    }


    public function fetch_top_gainers() {

        if( date('w') > 4 ) {
            return "Today is ".date('l');
        }

        // $page = new UrlDom();
        // $page->load("https://nepalstockinfo.com/gainers");
        // $gainers = $page->get_table_rows('//*[@id="home-contents"]/table/tbody/tr', 3);
        // $gainers = array_slice($gainers, 0, 10);

        $page = new UrlDom();
        $page->load("https://newweb.nepalstock.com/api/nots/top-ten/top-gainer?all=true");
        $gainers =  json_decode( $page->get_response() );


        for( $i=0; $i<20; $i++) {

            $arr1 = [
                'rank' => $i + 1,
                'date' => date('Y-m-d')
            ];

            $arr2 = [
                'symbol' => $gainers[$i]->symbol,
                'change' => $gainers[$i]->percentageChange,
            ];

            DB::table('top_gainers')->updateOrInsert($arr1, $arr2);
        }
    }


    function get_weekly_gainers($limit = 10) {

        $prev_date = DB::table('top_gainers')->select('date')->distinct()->orderBy('date', 'desc')->skip(1)->first()->date;

        $gainers = DB::table('top_gainers')->whereDate('date', '>=', $prev_date)->get();

        $avg = [];

        foreach( $gainers as $gainer ) {
            if ( isset( $avg[$gainer->symbol])) {
                $avg[$gainer->symbol] += $gainer->change;
            }

            else {
                $avg[$gainer->symbol] = $gainer->change;
            }
        }

        arsort($avg);

        return array_slice($avg, 0, $limit);
    }  


    public function get_top_losers($limit = 10) {

        if( date('w') > 4  || (date('G') > 10 && date('G') < 15) ) {
            return [];
        }

        $page = new UrlDom();
        $page->load("https://newweb.nepalstock.com/api/nots/top-ten/top-loser?all=true");
        $gainers =  json_decode( $page->get_response() );

        $arr = [];

        // dd($gainers);

        foreach( $gainers as $g ) {
            $arr[$g->symbol] = 0;
        }

        return $arr;
    }


    public function fetch_top_transactions() {

        if( date('w') > 4 ) {
            return "Today is ".date('l');
        }

        $page = new UrlDom();
        $page->load("https://newweb.nepalstock.com/api/nots/top-ten/transaction?all=true");

        $stocks = json_decode( $page->get_response() );

        for($i=0; $i<20; $i++) {
            $arr1 = [
                'rank' => $i + 1,
                'date' => date('Y-m-d')
            ];

            $arr2 = [
                'symbol' => $stocks[$i]->symbol,
                'quantity' => $stocks[$i]->totalTrades,
            ];

            DB::table('top_transactions')->updateOrInsert($arr1, $arr2);
        }

        return 0;
    }

    function get_weekly_transactions($limit = 10) {

        $prev_date = DB::table('top_gainers')->select('date')->distinct()->orderBy('date', 'desc')->skip(1)->first()->date;

        $gainers = DB::table('top_transactions')->whereDate('date', '>=', $prev_date)->get();

        $avg = [];

        foreach( $gainers as $gainer ) {
            if ( isset( $avg[$gainer->symbol])) {
                $avg[$gainer->symbol] += $gainer->quantity;
            }

            else {
                $avg[$gainer->symbol] = $gainer->quantity;
            }
        }

        arsort($avg);
        
        return array_slice($avg, 0, $limit);
    }









    public function fetch_top_volume() {

        if( date('w') > 4 ) {
            return "Today is ".date('l');
        }

        $page = new UrlDom();
        $page->load("https://newweb.nepalstock.com/api/nots/top-ten/trade?all=true");

        $stocks = json_decode( $page->get_response() );

        for($i=0; $i<count($stocks); $i++) {
            $arr1 = [
                'symbol' => $stocks[$i]->symbol,
                'date' => date('Y-m-d')
            ];

            $arr2 = [
                'volume' => $stocks[$i]->shareTraded
            ];

            DB::table('top_volume')->updateOrInsert($arr1, $arr2);
        }

        return 0;
    }




    function volume(Request $request) {

        $stocks_all = Stock::all();
        

        $page = new UrlDom();
        $page->load("https://merolagani.com/LatestMarket.aspx");
        $live_stocks = $page->get_table_rows('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr');

        $chg = [];
        $trade_vol = [];

        foreach( $live_stocks as $ls ) {
            $trade_vol[$ls[0]] = str_replace( ",", "", $ls[6] );
            $chg[$ls[0]] = $ls[2];
        }

        // dd($trade_vol);

        $prev_date = DB::table('top_volume')->whereDate('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->first()->date;

        if( $trade_vol[DB::table('top_volume')->whereDate('date', $prev_date)->first()->symbol] == DB::table('top_volume')->whereDate('date', $prev_date)->first()->volume ) {
            $prev_date = DB::table('top_volume')->whereDate('date', '<', $prev_date)->orderBy('date', 'desc')->first()->date;
        }

        foreach($stocks_all as $stock) {
            if( array_key_exists($stock->symbol, $trade_vol) ) {
                $stock['trade_vol'] = $trade_vol[$stock->symbol];

                $stock['30_vol_ratio'] =  round( $trade_vol[$stock->symbol] / floatval(str_replace(",","",$stock['30_day_avg_volume']))*100 );
                
                $stock['prev_vol'] = DB::table('top_volume')->where('symbol', $stock->symbol)->whereDate('date', $prev_date)->first();

                if( !is_null($stock['prev_vol']) ) {
                    $stock['prev_vol'] = $stock['prev_vol']->volume;
                }
                else {
                    $stock['prev_vol'] = -100;
                }

                $stock['prev_vol_ratio'] = round( ($stock['trade_vol'] / $stock['prev_vol']) * 100 );
                $stock['change'] = $chg[$stock->symbol];
                $stock->index = round(($stock['prev_vol_ratio'] / $stock['30_vol_ratio']) * ($stock['change']+1));

                if(is_nan($stock->index)) $stock->index = -100;
            }
        }

        if($request->summary)
            return view('nepse.volume-summary', ['stocks' => $stocks_all]);
        else
            return view('nepse.volume',['stocks' => $stocks_all]);
    }

    function testNepse() {
        $page = new UrlDom();
        $page->load("http://www.nepalstock.com/main/floorsheet?_limit=30000");
        echo $page->get_response();
        $live_stocks = $page->get_table_rows('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr');
    }

}
