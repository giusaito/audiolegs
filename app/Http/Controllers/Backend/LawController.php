<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Laravue\Models\Law;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Laravue\Models\User;

use Illuminate\Support\Facades\Hash;

class LawController extends Controller
{
    public function index(){
        
    }
    public function getList(){
        $leis = Law::orderBy('created_at', 'DESC')->get()->toTree();

        $response = [
            'data' => $leis
        ];

        return response()->json($response);
    }
    public function store(Request $request){
        // $fileFullPath = Storage::disk('local')->path('folder1/a.png');
        $fileFullPath = Storage::disk('local')->path('leis');
        dd($fileFullPath);
        dd($request);
    }
}