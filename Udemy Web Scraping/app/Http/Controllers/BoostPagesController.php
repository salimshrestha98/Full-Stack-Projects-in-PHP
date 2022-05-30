<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\BoostPage;

class BoostPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('boost.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bp = new BoostPage([
            'url' => $request->url,
            'phrases' => $request->phrases,
            'location' => $request->location
        ]);

        $path = $request->file('serp_url')->store('public/serps');
        $bp['serp_img'] = asset(Storage::url($path));

        $bp->save();
        
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bp = BoostPage::find($id);

        $img_top_margin = 30;
        $img_left_margin = 30;
        $img_line_spacing = 100;
        $line = 1;

        $phrases = explode(",",$bp['phrases'],2);

        $img = imagecreate(400, 200);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefilledrectangle($img, 0, 0, 500, 300, $white);
        foreach($phrases as $phrase) {
            imagestring($img, 5, $img_left_margin, 60*$line++, trim($phrase), $black);
        }
        
        imagepng($img, "phrases.png");
        $bp['phrase_img'] = url('/').'/phrases.png';

        return view('boost.show', ['boost_page' => $bp]);
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
        //
    }
}
