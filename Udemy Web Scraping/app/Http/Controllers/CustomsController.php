<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LfMeta;
use App\LfCourse;

class CustomsController extends Controller
{

    public function info() {
        for($i=5;$i<100;$i++) {
            $new_courses_count[$i] = LfCourse::where('visits',$i)->count();
        }
        
        $visits = LfMeta::where('name','like','%visits_on_%')->orderBy('created_at','desc')->take(30)->get();
        $temp = [];
        for($i=$visits->count()-1;$i>=0;$i--) {
            $temp[] = $visits[$i];
        }
        $visits = $temp;
        $top_courses = LfCourse::orderBy('visits','desc')->take(50)->get();
        $last_visited_courses = LfCourse::orderBy('updated_at','desc')->take(20)->get();


        return view('custom.info',[
            'new_courses_count' => $new_courses_count,
            'visits' => $visits,
            'top_courses' => $top_courses,
            'last_visited_courses' => $last_visited_courses
        ]);
    }

    // public function tweets() {
    //     $img_top_margin = 30;
    //     $img_left_margin = 30;
    //     $img_line_spacing = 100;
    //     $line = 1;

    //     $phrases = ['Lorem ipsum dolor sit','keto plus diet'];

    //     $img = imagecreate(400, 200);
    //     $white = imagecolorallocate($img, 255, 255, 255);
    //     $black = imagecolorallocate($img, 0, 0, 0);
    //     // imagefilledrectangle($img, 0, 0, 500, 300, $white);
    //     foreach($phrases as $phrase) {
    //         imagestring($img, 5, $img_left_margin, 60*$line++, $phrase, $black);
    //     }
        
    //     imagepng($img, "image.png");
    //     return '<a href="'.url('/').'/image.png">Image</a>';
    // }

    // public function sitemap() {
    //     $cs = LfCourse::paginate(5000);
    //     echo $cs->links();
    //     foreach($cs as $c) {
    //         echo '<a href="https://learn4free.fivescream.com/course/'.$c->name.'">'.$c->title.'</a><br>';
    //         // echo htmlspecialchars("<url><loc>https://learn4free.fivescream.com/course/".$c->name."</loc></url>");
    //     }
    // }

    // public function duplicates() {
    //     for($i=17750;$i<27000;$i += 1000) {
    //         $cs = LfCourse::where('id','>',$i)->where('id','<',$i+1000)->get();
    //         foreach($cs as $c) {
    //             $name = $c->name;
    //             $courses_count = LfCourse::where('name',$name)->count();
    //             echo $name."(".$courses_count.")<br>";
    //             while($courses_count > 1) {
    //                 LfCourse::where('name',$name)->orderBy('visits','asc')->first()->delete();
    //                 $courses_count = LfCourse::where('name',$name)->count();
    //             }
    //         }
    //     }
    // }

    // public function userfetch(Request $request) {
    //     return response()->json(['nem' => count($request->data)]);
    // }

    public function generateXML() {
        $myfile = fopen("sitemap.xml", "w") or die("Unable to open file!");
        $txt = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        fwrite($myfile, $txt);
        for($i=0;$i<35000;$i += 1000) {
            $courses = LfCourse::where('id','>',$i)->where('id','<',$i+1000)->get();
        
            foreach($courses as $course) {
                $txt = "\n<url>\n\t<loc>".url('/').'/course/'.$course->name."</loc>\n\t<changefreq>monthly</changefreq>\n\t<lastmod>".date('Y-m-d')."</lastmod>\n</url>";
                fwrite($myfile,$txt);
            }
        }
        $txt = "\n</urlset>";
        fwrite($myfile, $txt);
        fclose($myfile);
    }
}
