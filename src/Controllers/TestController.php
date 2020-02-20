<?php

namespace Hanoivip\Iap\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function __construct()
    {
        
    }
    
    public function callback(Request $request)
    {
        Log::debug("Test receive callback order:" . $request->get('order'));
        $proc = ['proc_ret' => 0];
        return $proc;
    }

}