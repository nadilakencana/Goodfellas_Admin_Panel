<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
class ContactUsController extends Controller
{
    //
    public function dataContact(){
        $contact = ContactUs::all();
        return view('contactUs.dataContactUs', compact('contact'));
    }


}
