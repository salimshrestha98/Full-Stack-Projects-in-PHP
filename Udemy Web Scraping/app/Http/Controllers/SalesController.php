<?php

namespace App\Http\Controllers;

use App\Email;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SalesController extends Controller
{
    public function mail(Request $request) {

        Email::insert(['name' => $request->cname,
                        'email' => $request->email,
                        'message' => $request->message,
                        'created_at' => date('y-m-d')]);

        return "Thank You!<br> Your message has been sent successfully.<br> It will be reviewed by our team.<br> You will be notified soon.";

        // $details = [
        //     'name' => $request->cname,
        //     'email' => $request->email,
        //     'subject' => "Testing Mail Function",
        //     'message' => $request->message,
        //     'from' => 'salimshrestha101@gmail.com',
        //     'to' => 'salimshrestha98@gmail.com',
        //     'headers' => "From:salimshrestha101@gmail.com"
        // ];
        // Mail::to("salimshrestha98@gmail.com")->send(new SendMail($details));
        // return  "Your message has been sent. Expect to get notified within 48 hours. Thank You.";
    }

    public function getMails() {
        $mails = Email::all();
        return view('emails',['mails' => $mails]);
    }
}
