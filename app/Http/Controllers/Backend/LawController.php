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
        // dd($request);
        // SE FOR RAIZ (ROOT)
        if(!$request->parent_id){

            // SE FOR PASTA
            if($request->type == 'folder'){
                $folder = Law::create([
                    'name' => $request->name,
                    'type' => $request->type,
                    'path' => $request->path,
                    'size' => $request->size
                ]);
                Storage::makeDirectory($request->path);
            // SE FOR ÁUDIO
            }else {
                $image = $request->file('file');
                $imageName = $image->getClientOriginalName();
                $path = $image->move(Storage::disk('local')->path($request->path),$imageName);
                
                $file = Law::create([
                    'name' => $imageName,
                    'type' => $request->type,
                    'path' => $request->path,
                    'size' => $path->getSize()
                ]);
                //dd(Storage::disk('local')->path($request->path));
                return response()->json(['success'=>$imageName]);
            }
        // SE FOR FILHO DE ALGUÉM
        }else {
            $parent = Law::where('id',$request->id)->first();

            // SE FOR PASTA
            if($request->type == 'folder'){
                Law::create([
                    'name' => $request->name,
                    'type' => $request->type,
                    'path' => $request->path.'/'.$request->name,
                    'parent_id' => $request->parent_id,
                    'size' => 0
                ], $parent);
                Storage::makeDirectory($request->path.'/'.$request->name);
            // SE FOR ÁUDIO
            }else {
                $image = $request->file('file');
                $imageName = $image->getClientOriginalName();
                $path = $image->move(Storage::disk('local')->path($request->path),$imageName);
                
                $file = Law::create([
                    'name' => $imageName,
                    'type' => $request->type,
                    'path' => $request->path.'/'.$request->name,
                    'parent_id' => $request->parent_id,
                    'size' => $path->getSize()
                ], $parent);
                return response()->json(['success'=>$imageName]);
                //dd(Storage::disk('local')->path($request->path));
            }
        }
        // return $request->parent_id;
        //$response = Storage::makeDirectory($request->path.'/'.$request->nome);
    }
}