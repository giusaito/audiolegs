<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Laravue\Models\Voucher;
use App\Http\Resources\VoucherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;

class VoucherController extends Controller
{
	public function index(){
		$vouchers = Voucher::orderBy('id', 'DESC')->with('plans')->paginate(10);

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
		$validator =  $validator = Validator::make($request->all(), [
            'chave' 				=> 'required|unique:vouchers|min:3|max:20',
            'quantidade_total' 		=> 'sometimes|required|numeric',
            'statusSwitch' 			=> 'required',
        ],
	    [
	    	'chave.required' => 'Por favor, preencha com a chave (nome) do cupom',
	    	'chave.unique' => 'Já existe um outro cupom com este nome',
	    	'chave.min' => 'Preencha no mínimo 3 caracteres para o nome do cupom',
	    	'chave.max' => 'Preencha no máximo 50 caracteres para o nome do cupom',
	    ]);
        if ($validator->fails()) {
            return response()->json(['errors' => 'Ops! Ocorreu um erro ao salvar o CUPOM! Por favor, verifique os campos e tente novamente'], 403);
        } else {
            // $desconto = str_replace('R$ ', '', $request->desconto);
            // $request->desconto = number_format((float)str_replace(",",".",str_replace(".","",$desconto)), 2, '.', '');

            $request->data_inicio = $request->data_expiracao[0];
            $request->data_fim = $request->data_expiracao[1];

            if($request->desconto == '0.00'){
                $request->desconto = null;
            }
            $voucher = Voucher::firstOrCreate([
                'chave' => $request->chave,
                'desconto' => $request->desconto,
                'desconto_porcentagem' => $request->desconto_porcentagem,
                'quantidade_total' => $request->quantidade_total,
                'quantidade_usado' => 0,
                'data_inicio' => $request->data_inicio,
                'data_fim' => $request->data_fim,
                'status' => $request->statusSwitch ? 'PUBLISHED' : 'UNPUBLISHED'
            ]);

           // if($voucher){
           //     return 'ok';
           // }else {
           //  return 'error';
           // }
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
        $request->data_inicio = $request->data_expiracao[0];
        $request->data_fim = $request->data_expiracao[1];

        if($request->desconto == '0.00'){
            $request->desconto = null;
        }
        $voucher                        = Voucher::findOrFail($id);
        $voucher->chave                 = $request->chave;
        $voucher->desconto              = $request->desconto;
        $voucher->desconto_porcentagem  = $request->desconto_porcentagem;
        $voucher->quantidade_total      = $request->quantidade_total;
        $voucher->quantidade_usado      = $request->quantidade_usado;
        $voucher->data_inicio           = $request->data_inicio;
        $voucher->data_fim              = $request->data_fim;
        $voucher->status                = $request->statusSwitch ? 'PUBLISHED' : 'UNPUBLISHED';
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
        $voucher = Voucher::findOrFail($id)->delete();

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

    public function savePlan(Request $request){
        dd('a');
    }
}