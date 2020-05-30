<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Laravue\Models\Activity;
use Illuminate\Http\Request;

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

}
