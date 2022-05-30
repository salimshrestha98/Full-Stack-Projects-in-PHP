<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

use App\Stock;
use App\UrlDom;

class UpdateStocks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(1000);

        $page = new UrlDom();
        $page->load("https://merolagani.com/LatestMarket.aspx");
        $live_stocks = $page->get_table_rows('//*[@id="ctl00_ContentPlaceHolder1_LiveTrading"]/table/tbody/tr');
    
        $stocks = [];

        foreach ($live_stocks as $l_s) {
            $sym = $l_s[0];
            $ltp = $l_s[1];

            $stocks[$sym] = $ltp;
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
                $sd = ($ls['52_high']-$ls['ltp'])/$ls['ltp'];
                $ls['yield'] = $ls['yield'] < 200 ? $ls['yield'] : 200;
                $sd = $sd ? $sd : 0.01;

                $ls['index_1'] = round(pow($ls['pe_ratio'],2.5)*$ls['pbv_ratio']) / (1 + ($ls['yield'] + pow($ls['return'], 2.5)));

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
}
