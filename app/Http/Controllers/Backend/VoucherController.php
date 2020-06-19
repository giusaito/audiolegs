<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Laravue\Models\Voucher;
use App\Http\Resources\VoucherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VoucherController extends Controller
{
	public function index(){
		$vouchers = Voucher::orderBy('id', 'DESC')->paginate(10);

        $response = [
            'pagination' => [
                'total' => $vouchers->total(),
                'per_page' => $vouchers->perPage(),
                'current_page' => $vouchers->currentPage(),
                'last_page' => $vouchers->lastPage(),
                'from' => $vouchers->firstItem(),
                'to' => $vouchers->lastItem()
            ],
            'data' => $vouchers
        ];

        return response()->json($response);
	}
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

  //   	$this->validate(request(), [
  //           'name' => 'required|max:191',
  //           'email' => 'required|email|unique:users|max:191',
  //           'password' => 'required|max:191'
  //       ]);




		// request()->validate([
		//         'file' => 'required',
		//         'type' => 'required'
		//     ],
		//     [
		//         'file.required' => 'You have to choose the file!',
		//         'type.required' => 'You have to choose type of the file!'
		//     ]);

		$this->validate(request(), [
	            'chave' 				=> 'required|unique:vouchers|min:3|max:20',
	            'desconto' 				=> 'required',
	            'desconto_porcentagem' 	=> 'required',
	            'quantidade_total' 		=> 'required|number',
	            'status' 				=> 'required|number',
	        ],
		    [
		    	'chave.required' => 'Por favor, preencha com a chave (nome) do cupom!',
		    	'chave.unique' => 'Já existe um outro cupom com este nome!',
		    	'chave.min' => 'Preencha no mínimo 3 caracteres para o nome do cupom!',
		    	'chave.max' => 'Preencha no máximo 50 caracteres para o nome do cupom!',
		    ]
	    );

       $voucher = Voucher::firstOrCreate([
                'chave' => $request->chave,
                'desconto' => $request->desconto,
                'desconto_porcentagem' => $request->desconto_porcentagem,
                'quantidade_total' => $request->quantidade_total,
                'quantidade_usado' => $request->quantidade_total,
                'data_inicio' => Carbon::parse($request->data_inicio)->format('d-m-Y H:i:s'),
                'data_fim' => Carbon::parse($request->data_fim)->format('d-m-Y H:i:s'),
                'status' => $request->statusSwitch ? 'PUBLISHED' : 'UNPUBLISHED'
            ]);

       if($voucher){
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
        $voucher = Voucher::findOrFail($id);
        $voucher->name = $request->name;
        $voucher->description = $request->description;
        $voucher->slug = Str::slug($request->name);
        $voucher->price = $request->price;
        $voucher->quantity_days = $request->days;
        $voucher->status = $request->statusSwitch ? 'PUBLISHED' : 'UNPUBLISHED';
        $voucher->update();

        if($voucher){
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
        $voucher = plan::findOrFail($id)->delete();

        if($voucher){
            return 'ok';
        } else {
            return 'erro';
        }
    }

    public function getList(){
        $cupons = Voucher::orderBy('created_at', 'DESC')->get();

        $response = [
            'data' => $cupons
        ];

        return response()->json($response);
    }

    public function getChave(Request $request){
        // echo $request->input('0');
        $chave = Voucher::where('chave', '=', $request->input('chave'))->first();
        if ($chave != null) {
            $response = ['count'=>1];
        }else {
            $response = ['count'=>0];
        }
        return response()->json($response);
    }
}