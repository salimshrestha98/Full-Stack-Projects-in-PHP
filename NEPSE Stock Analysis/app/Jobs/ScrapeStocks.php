<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Stock;

use App\UrlDom;

class ScrapeStocks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(1000);

        $last_index = Stock::orderBy('id','desc')->first();
        if($last_index) {
            sleep(5);
            $current_index = Stock::orderBy('id','desc')->first();
            if( $last_index->id != $current_index->id) {
                return 0;
            }
            elseif($last_index->id > 150) {
                Stock::truncate();
            }
        }     
        
        $skipped_stocks = ['CHDC','SNLB'];

        $stocks_list = ['ACLBSL','ADBL','AHPC','AIL','AKJCL','AKPL','ALBSL','ALICL','API','BARUN','BFC','BOKL','BPCL','CBBL','CBL','CCBL','CFCL','CGH','CHCL','CHL','CLBSL','CORBL','CZBIL','DDBL','DHPL','EBL','EDBL','EIC','FMDBL','FOWAD','GBBL','GBIME','GBLBS','GFCL','GHL','GIC','GILB','GLBSL','GLH','GLICL','GMFBS','GMFIL','GRDBL','GUFL','HDHPC','HGI','HIDCL','HPPL','HURJA','ICFC','IGI','ILBS','JBBL','JBLB','JFL','JLI','JOSHI','JSLBB','KBL','KLBSL','KMCDB','KPCL','KRBL','KSBBL','LBBL','LBL','LEC','LGIL','LICN','LLBS','MBL','MDB','MEGA','MEN','MERO','MFIL','MHNL','MKLB','MLBBL','MLBL','MLBSL','MMFDB','MNBBL','MPFL','MSLB','NABBC','NABIL','NBB','NBL','NCCB','NFS','NGPL','NHDL','NHPC','NICA','NICL','NICLBSL','NIFRA','NIL','NLBBL','NLG','NLIC','NLICL','NMB','NMBMF','NMFBS','NRIC','NRN','NUBL','OHL','PCBL','PFL','PIC','PICL','PLI','PLIC','PMHPL','PPCL','PRIN','PROFL','PRVU','RADHI','RHPC','RHPL','RLFL','RLI','RMDC','RRHP','RSDC','RURU','SABSL','SADBL','SANIMA','SAPDBL','SBI','SBL','SCB','SDLBSL','SFCL','SGI','SHEL','SHINE','SHIVM','SHL','SHPC','SIC','SICL','SIFC','SIL','SINDU','SJCL','SKBBL','SLBBL','SLBS','SLBSL','SLI','SLICL','SMATA','SMB','SMFBS','SMFDB','SPDL','SRBL','SSHL','SWBBL','TRH','UFL','UIC','ULI','UMHL','UMRH','UNHPL','UPCL','UPPER','USLB','VLBS','WOMI'];

        $urldom = new UrlDom;

        foreach( $stocks_list as $stock_sym) {
            if(Stock::where('symbol',$stock_sym)->doesntExist()) {

                $urldom->load("https://merolagani.com/CompanyDetail.aspx?symbol=".$stock_sym);
            
                $stock = [];

                $stock['symbol'] = $stock_sym;
                $stock['name'] = $urldom->get_string('//*[@id="ctl00_ContentPlaceHolder1_CompanyDetail1_companyName"]');
                $stock['sector'] = trim($urldom->get_string('//*[@id="accordion"]/tbody[1]/tr/td'));
                $stock['ltp'] = $urldom->get_string('//*[@id="ctl00_ContentPlaceHolder1_CompanyDetail1_lblMarketPrice"]');
                $_52_high_low = $urldom->get_string('//*[@id="accordion"]/tbody[6]/tr/td');
                $stock['52_high'] = substr($_52_high_low,0,strpos($_52_high_low,"-"));
                $stock['52_low'] = substr($_52_high_low,strpos($_52_high_low,"-")+1,strlen($_52_high_low));
                $stock['120_avg'] = $urldom->get_string('//*[@id="accordion"]/tbody[8]/tr/td');
                
                $stock['yield'] = $urldom->get_string('//*[@id="ctl00_ContentPlaceHolder1_CompanyDetail1_lblYearYeild"]');
                $stock['eps'] = $urldom->get_string('//*[@id="accordion"]/tbody[10]/tr/td');
                $stock['eps'] = substr($stock['eps'],0,strpos($stock['eps'],"("));
                $stock['pe_ratio'] = $urldom->get_string('//*[@id="accordion"]/tbody[11]/tr/td');
                $stock['book_value'] = $urldom->get_string('//*[@id="accordion"]/tbody[12]/tr/td');
                $stock['pbv_ratio'] = $urldom->get_string('//*[@id="accordion"]/tbody[13]/tr/td');

                $_dividend = $urldom->get_string('//*[@id="accordion"]/tbody[14]/tr[1]/td');
                $_dividend = trim(substr($_dividend,1,strpos($_dividend,"(")-1));
                $_bonus = $urldom->get_string('//*[@id="accordion"]/tbody[15]/tr[1]/td');
                $_bonus = trim(substr($_bonus,1,strpos($_bonus,"(")-1));
                
                $stock['listed_shares'] = $urldom->get_string('//*[@id="divAbout"]//td');           
                $stock['market_cap'] = $urldom->get_string('//*[@id="accordion"]/tbody[18]/tr/td');
                $stock['30_day_avg_volume'] = $urldom->get_string('//*[@id="accordion"]/tbody[17]/tr/td');         
            
                
                $stock['ltp'] = floatval(str_replace(',','',$stock['ltp']));
                $stock['52_high'] = floatval(str_replace(',','',$stock['52_high']));
                $stock['52_low'] = floatval(str_replace(',','',$stock['52_low']));
                $stock['120_avg'] = floatval(str_replace(',','',$stock['120_avg']));
                $stock['yield'] = floatval(str_replace(',','',$stock['yield']));
                $stock['eps'] = floatval(str_replace(',','',$stock['eps']));
                $stock['pe_ratio'] = floatval(str_replace(',','',$stock['pe_ratio']));
                $stock['book_value'] = floatval(str_replace(',','',$stock['book_value']));
                $stock['pbv_ratio'] = floatval(str_replace(',','',$stock['pbv_ratio']));

                $stock['dividend'] = floatval(str_replace([',',' '],'',$_dividend));
                $stock['bonus'] = floatval(str_replace([',',' '],'',$_bonus));
                $_qty = 100;
                $stock['return'] = ($_qty*$stock['dividend']+floor($stock['bonus']/100*$_qty)*$stock['ltp'])/($_qty*$stock['ltp']);

                $stock['yield'] = $stock['yield']?$stock['yield']:1;
                $sd = ($stock['52_high']-$stock['ltp'])/$stock['ltp'];
                $sd = $sd ? $sd : 0.01;
                $stock['index_1'] = round(10*$stock['pe_ratio']*$stock['pbv_ratio']/(sqrt($stock['yield'])*$sd),2);
                $nt_pe = $stock['pe_ratio'] > 0 ? $stock['pe_ratio'] : 1;
                $stock['index_2'] = round($nt_pe*$stock['pbv_ratio']/$sd,2);

                if(abs($stock['index_1'])>999999 || is_nan($stock['index_1'])) {
                    $stock['index_1'] = 0;
                }

                if(abs($stock['index_2'])>999999 || is_nan($stock['index_2'])) {
                    $stock['index_2'] = 0;
                }

                foreach($stock as $st) {
                    if(is_float($st) || is_double($st)) {
                        $st = round($st,2);
                    }
                }

                $stocka = new Stock($stock);
                if(!is_nan($stock['index_1']))
                    $stocka->save();          
            }
        }
    }
}
