<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Laravue\Models\Activity;
use Illuminate\Http\Request;

use App\Laravue\Models\User;

use Illuminate\Support\Facades\Hash;

class ActivitieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = Activity::orderBy('id', 'DESC')->paginate(20);

       $response = [
           'pagination' => [
               'total' => $activities->total(),
               'per_page' => $activities->perPage(),
               'current_page' => $activities->currentPage(),
               'last_page' => $activities->lastPage(),
               'from' => $activities->firstItem(),
               'to' => $activities->lastItem()
           ],
           'data' => $activities
       ];

       return response()->json($response);
    }
    
     public function userImport(Request $request){

        foreach($request->all() as $key => $value) {
          // foreach ($value as $ket => $v) {
             $user = user::firstOrCreate([
              'name' => $value['data'][$key],
              'email' => $value['data'][$key],
              'password' => 12345,
            ]);
          // }
         
            // $user = user::firstOrCreate([
            //   'name' => $value['data'][$key],
            //   'email' => $value['data'][$key],
            //   'password' => 12345,
            // ]);
        }


        return 'ok';
    }

}
