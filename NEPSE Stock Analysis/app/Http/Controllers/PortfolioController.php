<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Stock;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = DB::table('stocks')->get();
        $portfolio = DB::table('portfolio')->orderBy('symbol','asc')->get();

        foreach( $portfolio as $stock) {
            $ltp = DB::table('stocks')->where('symbol', $stock->symbol)->first()->ltp;
            $stock->ltp = $ltp;
            $level_prices = [
                's_65' => $stock->s_65,
                's_40' => $stock->s_40,
                's_25' => $stock->s_25,
                's_15' => $stock->s_15,
                's_10' => $stock->s_10,
                's_5' => $stock->s_5
            ];


            foreach( $level_prices as $class => $price) {
                if( ($ltp > $price - $price*0.03) && $ltp < $price + $price*0.01 ) {
                    $stock->bg_class = $class;
                    $stock->bg_color = "bg-warning";
                    break;
                }

                elseif( $ltp > $price ) {
                    $stock->bg_class = $class;
                    $stock->bg_color = "bg-success";
                    break;
                }
            }
        }

        return view('portfolio.index', [
            'stocks' => $stocks,
            'portfolio' => $portfolio
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('portfolio')->insert([
            'symbol' => $request->symbol,
            'buy_price' => $request->price,
            'rally_low' => $request->rally,
            's_5' => $request->rally + $request->rally*0.05,
            's_10' => $request->rally + $request->rally*0.10,
            's_15' => $request->rally + $request->rally*0.15,
            's_25' => $request->rally + $request->rally*0.25,
            's_40' => $request->rally + $request->rally*0.40,
            's_65' => $request->rally + $request->rally*0.65,
        ]);

        return redirect('/portfolio');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('portfolio')->where('id', $id)->delete();

        return redirect('/portfolio');
    }
}
