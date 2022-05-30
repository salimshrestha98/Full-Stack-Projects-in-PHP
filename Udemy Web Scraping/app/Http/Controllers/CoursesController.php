<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LfCourse;
use App\LfMeta;
use App\BoostPage;

set_time_limit(10000);

class CoursesController extends Controller
{
    public function curlGet($url) {
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
        return $xmlPageXPath;
    }

    public function getCourseLinksFromXml($xmlLink) {
        $xm = curl_init();  // Initialising cURL session
        // Setting cURL options
        curl_setopt($xm, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($xm, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($xm, CURLOPT_URL, $xmlLink);
        $results = curl_exec($xm);  // Executing cURL session
        curl_close($xm);

        $xml = simplexml_load_string($results) or die("Error: Cannot create object");

        $res = [];
        foreach($xml as $x) {
            $res[] = $x->loc[0];
        }

        return $res;
    }

    public function getNodeValue($obj) {
        if(is_object($obj)) return $obj->nodeValue;
        else return "";
    }

    public function getAndSaveCourse($course_url) {
        
        $url = $course_url;
        $page = $this->curlGet($url);


        $rankers = $this->getNodeValue($page->query('//div[@class="styles--rating-wrapper--5a0Tr"]')[0]);

        $st_pos =  strpos($rankers,"(");
        $sp_pos = strpos($rankers," ",$st_pos);
        $ranke = substr($rankers,$st_pos+1,$sp_pos-$st_pos);
        $rankers = str_replace(",","",$ranke);

        if($rankers > 100) {

            $url_parts = explode('/',$url);
            $course = new LfCourse;
            $course['url'] = $url;
            $course['title'] = $page->query('//h1[@class="udlite-heading-xl clp-lead__title clp-lead__title--small"]')[0]->nodeValue;
            $course['subtitle'] = $page->query('//div[@class="udlite-text-md clp-lead__headline"]')[0]->nodeValue;

            $course['authors'] = $page->query('//span[@class="instructor-links--names--7UPZj"]')[0]->nodeValue;
            $course['authors'] = substr($course['authors'],11);

            $wyl_raw = $page->query('//ul[@class="unstyled-list udlite-block-list what-you-will-learn--objectives-list--2cWZN"]/li');
            $course['wyl'] = '<ul>';
            foreach($wyl_raw as $wyl) {
                $course['wyl'] .= "<li>".$wyl->nodeValue.'</li>';
            }
            $course['wyl'] .= '</ul>';


            $d_raw = $page->query('//div[@class="show-more--content--isg5c show-more--with-gradient--2abmN"]/div/div/p');
            $course['description'] = '<div>';
            foreach($d_raw as $d) {
                $course['description'] .= "<p>".$d->nodeValue.'</p>';
            }
            $course['description'] .= '</div>';


            $wtcif_raw = $page->query('//ul[@class="styles--audience__list--3NCqY"]/li');
            $course['wtcif'] = '<ul>';
            foreach($wtcif_raw as $wtcif) {
                $course['wtcif'] .= "<li>".$wtcif->nodeValue.'</li>';
            }
            $course['wtcif'] .= '</ul>';

            $course['img_url'] = $page->query('//span[@class="intro-asset--img-aspect--1UbeZ"]/img/@src')[0];
            $course['img_url'] = is_object($course['img_url']) ? $course['img_url']->nodeValue : "#";
            $course['name'] = $url_parts[count($url_parts)-2];

            $fs_raw = $page->query('//span[@class="curriculum--content-length--1XzLS"]/span/span')[0]->nodeValue;
            for($i=0;$i<strlen($fs_raw); $i++) {
                if(!is_numeric($fs_raw[$i])) {
                    $course['filesize'] = number_format(substr($fs_raw,0,$i)/3.14,2);
                    break;
                }
            }

            $breadcrumbs = $page->query('//div[@class="topic-menu udlite-breadcrumb"]/a');
            $course['cat_1'] = strtolower(str_replace(" ","-",str_replace(" & ","-",$breadcrumbs[0]->nodeValue)));
            $course['cat_2'] = strtolower(str_replace(" ","-",str_replace(" & ","-",$this->getNodeValue($breadcrumbs[1]->nodeValue))));
            $course['cat_3'] = strtolower(str_replace(" ","-",str_replace(" & ","-",$this->getNodeValue($breadcrumbs[2]))));

            echo $course['title'].'<hr>';

            $course->save();
        }
    }

    public function course_exists($url) {
        $course = LfCourse::where('url',$url)->count();
        if($course) return true;
        else return false;
    }

    public function start() {
        $page_index = LfMeta::where('name', 'page_index')->first()->value;
        $v1 = LfMeta::where('name', 'url_index')->first()->value;
        sleep(5);
        $v2 = LfMeta::where('name', 'url_index')->first()->value;
        if($v1 != $v2) {
            return "Process Already Running!";
        }
        $r = 20;
        while($r--) {
            $sitemap_page = 'https://www.udemy.com/sitemap/courses.xml?p='.$page_index;
            $xmlLinks = $this->getCourseLinksFromXml($sitemap_page);
            $url_index = LfMeta::where('name', 'url_index')->first();
            $url_index->value += rand(0,10);
            if($url_index->value > 98 ){
                LfMeta::where('name', 'page_index')->first()->update(['value' => ++$page_index]);
            }
            $url_index->value %= 99;
            $url_index->save();
            for($i=$url_index->value; $i<count($xmlLinks); $i++) {
                $link = $xmlLinks[$i];
                if(!$this->course_exists($link)) {
                    $this->getAndSaveCourse($link);
                    LfMeta::where('name', 'url_index')->first()->update(['value' => $i]);
                }  
            }
            LfMeta::where('name', 'page_index')->first()->update(['value' => ++$page_index]);
            LfMeta::where('name', 'url_index')->first()->update(['value' => 0]);
        }
    }

/*
    The codebase to show course details.
    Route => /course/{name}
*/

    public function show(Request $request,$cname = null) {
            $prev_visits = LfMeta::where('name','visits')->first();
            $prev_visits->value++;
            $prev_visits->save();

        $course = LfCourse::where('name',$cname)->first();
        if($course) {
            $course->visits++;
            $course->save();

            $similar_courses = LfCourse::where('cat_1',$course['cat_1'])
                                        ->where('id','!=',$course->id)
                                        ->orderBy('visits','desc')
                                        ->offset(20)
                                        ->limit(12)->get();

            $new_courses = LfCourse::orderBy('visits','asc')->take(12)->get();
            

            return view('course.view',[
                'course' => $course,
                'similar_courses' => $similar_courses,
                'new_courses' => $new_courses,
                'random_courses' => $this->getRandomCourses(12),
                'user_ip' => isset($_SERVER['user_ip']) ? $_SERVER['user_ip'] : '',
                'user_city' => isset($_SERVER['user_city']) ? $_SERVER['user_city'] : '',
                'user_country' => isset($_SERVER['user_country']) ? $_SERVER['user_country'] : ''
                ]);
        }
        else
            return view('course.notfound',['cname' => $cname]);
    }








/*
    The Codebase to show homepage.
    Top 15 most visited courses are displayed with pagination.
*/

    public function home() {
        $courses = LfCourse::orderBy('visits','desc')->simplePaginate(50);
        $new_courses = LfCourse::orderBy('visits','asc')->take(12)->get();
        return view('home',['courses' => $courses,
                            'new_courses' => $new_courses,
                            'random_courses' => $this->getRandomCourses(12)]);
    }


    public function editList(Request $request) {
        if(isset($request->q)) {
            $q = $request->q;
            $courses = LfCourse::where('title','like','%'.$q.'%')
            ->orWhere('subtitle','like','%'.$q.'%')
            ->orWhere('authors','like','%'.$q.'%')
            ->distinct()->limit(100)->get();
            return view('course.edit_list',['courses' => $courses]);
        }

        return view('course.edit_list',['courses' => []]);
    }

    public function edit(Request $request, $id) {
        $course = LfCourse::where('id',$id)->first();
        if($course) {
            return view('course.edit',['course' => $course]);
        }
        else return redirect('/');
    }

    public function update(Request $request) {
        $course = LfCourse::where('id',$request->id)->first();
        if($course) {
            $course->filename = $request->filename;
            $course->filesize = $request->filesize;
            $course->file_url = $request->file_url;
            $course->torrent_url = $request->torrent_url;
            $course->save();
            return redirect('/course/edit');
        }
    }












/*
    The Codebase to show courses according to Category.
    Route => get('/category');
    Parameters => q=[category-name];
 */

    public function showCategory(Request $request) {
        $category = $request->q;
        if(!$category)
            $this->home();

        else {
            $courses = LfCourse::where('cat_1',$category)->orderBy('visits','desc')->simplePaginate(15)->appends(['q' => $category]);
            $category = str_replace("-"," ",$category);
            $category = ucwords($category);
            return view('category',[
                'courses' => $courses,
                'category' => $category
            ]);
        }
    }

    public function search(Request $request) {
        $q = $request->q;
        $courses = LfCourse::where('title','like','%'.$q.'%')
                            ->distinct()
                            ->orderBy('visits','desc')
                            ->simplePaginate(15)
                            ->appends(['q' => $q]);

        return view('search',[
            'courses' => $courses
        ]);
    }











    // public function download(Request $request,$name) {
    //     $visits_q = "visits_on_".date('Y_m_d');
    //     $visits_result = LfMeta::where('name',$visits_q)->first();

    //     if( $visits_result ) {
    //         $visits_result->value++;
    //         $visits_result->save();
    //     }
    //     else {
    //         $visits_meta = new LfMeta;
    //         $visits_meta->name = $visits_q;
    //         $visits_meta->value = 1;
    //         $visits_meta->save();
    //     }
        
    //     $course = LfCourse::where('name',$name)->first();

    //     if($course)
    //         return view('course.download', ['course' => $course]);
    //     else
    //         return view('course.notfound');
    // }











    public function download(Request $request,$name) {
        $visits_q = "visits_on_".date('Y_m_d');
        $visits_result = LfMeta::where('name',$visits_q)->first();

        if( $visits_result ) {
            $visits_result->value++;
            $visits_result->save();
        }
        else {
            $visits_meta = new LfMeta;
            $visits_meta->name = $visits_q;
            $visits_meta->value = 1;
            $visits_meta->save();
        }

        $course = LfCourse::where('name',$name)->first();
        if($course->visits && !$request->has('vcode')) {
            $bp = BoostPage::find(4);
            $img_left_margin = 30;
            $line = 1;
    
            $phrases = explode(",",$bp['phrases'],2);
    
            $img = imagecreate(400, 200);
            $white = imagecolorallocate($img, 220, 220, 220);
            $black = imagecolorallocate($img, 150, 150, 150);
            imagefilledrectangle($img, 0, 0, 500, 300, $white);
            foreach($phrases as $phrase) {
                imagestring($img, 5, $img_left_margin, 60*$line++, trim($phrase), $black);
            }
            
            imagepng($img, "phrases.png");
            $bp['phrase_img'] = url('/').'/phrases.png';

            return view('course.verify',['boost_page' => $bp,'course' => $course]);
        }
        else {
            $code_is_valid = true;
            if($course)
                if($code_is_valid)
                    return view('course.download', ['course' => $course]);
                else 
                    return redirect('/course/'.$course->name);
            else
                return view('course.notfound');
        }
    }











    public function getlink(Request $request) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 32; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $url = 'https://drive.google.com/drive/folders/'.$randomString.'?usp=sharing';

        return response()->json([
            'url' => $url
        ]);
    }

    public function coupon(Request $request,$cname = null) {
        $course = LfCourse::where('name',$cname)->first();
        $coupon_options = ['FREE100','100OFF','FREECOUPON','100COUPON','TOTALLYFREE','FREE2021','ENJOY100','2021OFF100','HAPPY2020','HAPPY2021','FREE2021','FREE1000','200OFF','MARCHDEAL','BESTCOURSE','DOWNLOAD100OFF','BESTCOURSE'];
        $coupon = $coupon_options[strlen($course->name)%count($coupon_options)];
        $coupon_health = strlen($coupon)%6*10;
        return view('course.coupon',['course' => $course,
        'coupon' => $coupon,
        'coupon_health' => $coupon_health]);
    }

    public function getRandomCourses($c = 12) {
        $lastCourse = LfCourse::orderBy('id','desc')->first();
        $lastIndex = $lastCourse->id;
        $randomCourses = [];
        while($c--) {
            $course = LfCourse::find(mt_rand(10,$lastIndex));
            // print_r($course);
            // echo "<br><hr><br>";
            if($course) {
                $randomCourses[] = $course;
            }
            else $c++;
        }

        return $randomCourses;
    }

}
