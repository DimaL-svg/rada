<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPanel extends Controller
{
    public function adminpanel(){
return view('adminlte::page');
    }
    
}
