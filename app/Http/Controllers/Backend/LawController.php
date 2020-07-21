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
        // SE FOR RAIZ (ROOT)
        $request->parent_id = intval($request->parent_id);
        if(!$request->parent_id){
            // SE FOR PASTA
            if($request->type == 'folder'){
                $folder = Law::create([
                    'name' => $request->name,
                    'type' => $request->type,
                    'path' => $request->path.'/'.$request->name,
                    'size' => 0
                ]);
                Storage::makeDirectory('/public/'.$request->path.'/'.$request->name);
            // SE FOR ÁUDIO
            }else {
                dd($request->all());
                $audio = $request->file('file');
                $audioName = $audio->getClientOriginalName();
                $path = $audio->move(Storage::disk('local')->path('/public/'.$request->path),$audioName);
                $file = Law::create([
                    'name' => $audioName,
                    'type' => $request->type,
                    'path' => $request->path,
                    'size' => $path->getSize()
                ]);
                //dd(Storage::disk('local')->path($request->path));
                return response()->json(['success'=>$audioName]);
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
                Storage::makeDirectory('/public/'.$request->path.'/'.$request->name);
            // SE FOR ÁUDIO
            }else {
                $audio = $request->file('file');
                $audioName = $audio->getClientOriginalName();
                $path = $audio->move(Storage::disk('local')->path('/public/'.$request->path),$audioName);
                $file = Law::create([
                    'name' => $audioName,
                    'type' => $request->type,
                    'path' => $request->path.'/'.$request->name,
                    'parent_id' => $request->parent_id,
                    'size' => $path->getSize()
                ], $parent);
                return response()->json(['success'=>$audioName]);
            }
        }
    }
    public function getItem($id){
        $item = Law::where('id',$id)->first();
        return response()->json($item);
    }
    public function saveInfo(Request $request){
        // dd($request->audio_name);
        $item = Law::findOrFail($request->id);
        $item->update($request->all());
    }
    public function destroy(Request $request){
        // 2, 11, 12, 13, 14
        // dd(Law::findOrFail($request->lei['id']));
        $lei = Law::findOrFail($request->lei['id'])->delete();
        return response()->json($lei);
    }

    public function update(Request $request){
        dd($request->all());
    }
}