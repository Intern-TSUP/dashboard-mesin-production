<?php

namespace App\Http\Controllers\System\ContactUs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index()
    {
        // Logic to display the contact us page
        return view('v1.contact');
    }
}
