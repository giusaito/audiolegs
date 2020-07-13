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
    public function getList($id = null){
        if($id){
            $leis = Law::where('parent_id',$id)->orderBy('created_at', 'DESC')->get()->toTree();
        } else {
            $leis = Law::orderBy('created_at', 'DESC')->get()->toTree();
        }
        $response = [
            'data' => $leis
        ];

        return response()->json($response);
    }
    public function store(Request $request){
        //$fileFullPath = Storage::disk('local')->path('leis');
        if(!$request->parent_id){
            if($request->type == 'folder'){
                $folder = Law::create([
                    'name' => $request->name,
                    'type' => $request->type,
                    'path' => $request->path,
                    'size' => 0
                ]);
            }else {
               
            }
        }else {
            $parent = Law::where('id',$request->id)->first();
            if($request->type == 'folder'){
                Law::create([
                    'name' => $request->name,
                    'type' => $request->type,
                    'path' => $request->path,
                    'parent_id' => $request->parent_id,
                    'size' => 0
                ], $parent);
                // $attributes = [
                //     'name'      => $request->name,
                //     'type'      => $request->type,
                //     'path'      => $request->path,
                //     'parent_id' => $request->parent_id,
                //     'size'      => 0
                // ];
                // $parent->children()->create($attributes);
            }else {
               
            }
        }
        return $request->parent_id;
        //$response = Storage::makeDirectory($request->path.'/'.$request->nome);
    }
}