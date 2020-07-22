<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Laravue\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function getList(){
        $playlists = Playlist::with('user','audios')->orderBy('created_at', 'ASC')->get();
        $response = [
            'data' => $playlists
        ];

        return response()->json($response);
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
        // $validator =  $validator = Validator::make($request->all(), [
        //     'name'                  => 'required|unique:playlists|min:3|max:20',
        //     'description'           => 'required',
        // ],
        // [
        //     'name.required' => 'Por favor, preencha com o nome da playlist',
        //     'name.unique' => 'Já existe um outra playlist com este nome',
        //     'name.min' => 'Preencha no mínimo 3 caracteres para o nome da playlist',
        //     'name.max' => 'Preencha no máximo 50 caracteres para o nome da playlist',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['errors' => 'Ops! Ocorreu um erro ao salvar a PLAYLIST! Por favor, verifique os campos e tente novamente'], 403);
        // } else {
            // dd($request->all());
            $playlist = Playlist::firstOrCreate([
                'name' => $request->name,
                'description' => $request->description,
                'cover_image' => $request->cover_image,
                'status' => $request->status,
                'type' => $request->type,
                'author_id' => Auth::user()->id,
            ]);
            return Response::json(array('success' => true, 'last_insert_id' => $playlist->id), 200);
        // }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Playlist $playlist)
    {
        // dd($request->all());
        // $playlist->update($request->all());
        $playlist->name = $request->name;
        $playlist->description = $request->description;
        $playlist->cover_image = 'adfasd.jpg';
        $playlist->status = $request->status;
        $playlist->type = $request->type;
        $playlist->update();

        return Response::json(array('success' => true, 'last_insert_id' => $playlist->id), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function show(Playlist $playlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Playlist $playlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Playlist $playlist)
    {
        //
    }

    public function uploadCover(Request $request, $id){
        // Define o valor default para a variável que contém o nome da imagem 
        $nameFile = null;
    
        // Verifica se informou o arquivo e se é válido
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            
            // Define um aleatório para o arquivo baseado no timestamps atual
            $name = uniqid(date('HisYmd'));
    
            // Recupera a extensão do arquivo
            $extension = $request->image->extension();
    
            // Define finalmente o nome
            $nameFile = "{$name}.{$extension}";
    
            // Faz o upload:
            $upload = $request->image->storeAs('playlist/cover', $nameFile);
            // Se tiver funcionado o arquivo foi armazenado em storage/app/public/playlist/cover/nomedinamicoarquivo.extensao
    
            // Verifica se NÃO deu certo o upload (Redireciona de volta)
            if ( !$upload ){
                Playlist::findOrFail($id)->delete();
                return Response::json(array('success' => false, 'message' => 'Não foi possível efetuar o envio da capa da playlist. Por favor, tente novamente.'), 200);
            }else {
                $playlist               = Playlist::findOrFail($id);
                $playlist->cover_image  = $nameFile;
                $playlist->update();
            }
    
        }
    }
}
