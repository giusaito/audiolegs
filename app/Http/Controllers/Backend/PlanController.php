<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Laravue\Models\Plan;
use App\Http\Resources\PlanResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function getUserIP(){
      // Get real visitor IP behind CloudFlare network
      if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
      }
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];

      if(filter_var($client, FILTER_VALIDATE_IP)){
          $ip = $client;
      } elseif(filter_var($forward, FILTER_VALIDATE_IP)){
          $ip = $forward;
      }
      else{
          $ip = $remote;
      }

      return $ip;
    }

    public function index()
    {
        $plans = Plan::orderBy('id', 'DESC')->paginate(10);

        $response = [
            'pagination' => [
                'total' => $plans->total(),
                'per_page' => $plans->perPage(),
                'current_page' => $plans->currentPage(),
                'last_page' => $plans->lastPage(),
                'from' => $plans->firstItem(),
                'to' => $plans->lastItem()
            ],
            'data' => $plans
        ];

        return response()->json($response);
    }

    public function all($voucher)
    {
        // dd(Plan::withCount('vouchers')->get());
        $plans = Plan::orderBy('plans.id', 'DESC')->with(['vouchers' => function($query) use ($voucher) {
                $query->where(['vouchers.id'=>$voucher])->select(['vouchers.id', 'chave', 'desconto', 'desconto_porcentagem', 'quantidade_total', 'quantidade_usado', 'data_inicio', 'data_fim', 'status' ]);
            }
        ])->get();
        // dd(response()->json($response));
        return response()->json($plans);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $plan = Plan::firstOrCreate([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => Str::slug($request->name),
                'price' => $request->price,
                'quantity_days' => $request->days,
                'user_id' => Auth::user()->id,
                'status' => $request->statusSwitch ? 'PUBLISHED' : 'DRAFT',
                'user_ip_created' => $this->getUserIP()
            ]);

       if($plan){
           return 'ok';
       }else {
        return 'error';
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->name = $request->name;
        $plan->description = $request->description;
        $plan->slug = Str::slug($request->name);
        $plan->price = $request->price;
        $plan->quantity_days = $request->days;
        $plan->status = $request->statusSwitch ? 'PUBLISHED' : 'DRAFT';
        $plan->update();

        if($plan){
            echo 'ok';
        } else {
            echo 'error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = plan::findOrFail($id)->delete();

        if($plan){
            return 'ok';
        } else {
            return 'erro';
        }
    }
}