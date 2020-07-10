<?php

namespace App\Http\Controllers\Backend\Laws;

use App\Http\Controllers\Controller;
use App\Laravue\Models\Laws\Law;
use Illuminate\Http\Request;

use App\Laravue\Models\User;

use Illuminate\Support\Facades\Hash;

class LawController extends Controller
{
    public function index(){
        
    }
    public function store(Request $request){
        dd($request);
    }
}