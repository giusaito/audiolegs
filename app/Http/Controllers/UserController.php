<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use App\Laravue\Models\userProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    const ITEM_PER_PAGE = 15;

    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $userQuery = User::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role = Arr::get($searchParams, 'role', '');
        $university = Arr::get($searchParams, 'universidade', '');
        $state = Arr::get($searchParams, 'estado', '');
        $city = Arr::get($searchParams, 'cidade', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        $userQuery->whereHas('roles', function($q) {
          $q->whereIn('id',[1,2,3]);
        });
        // dd($userQuery);

        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($keyword)) {
            $userQuery->where('name', 'LIKE', '%' . $keyword . '%');
            $userQuery->orWhere('email', 'LIKE', '%' . $keyword . '%');
        }

        if (!empty($university)) {
            $userQuery->where('university_id', $university);
        }

        if (!empty($state)) {
            $userQuery->where('state_id', $state);
        }

        if (!empty($city)) {
            $userQuery->where('city_id', $city);
        }

        return UserResource::collection($userQuery->paginate($limit));
    }
    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function visitors(Request $request)
    {
        $searchParams = $request->all();
        $userQuery = User::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role = Arr::get($searchParams, 'role', '');
        $university = Arr::get($searchParams, 'universidade', '');
        $state = Arr::get($searchParams, 'estado', '');
        $city = Arr::get($searchParams, 'cidade', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        $userQuery->whereHas('roles', function($q) {
          $q->whereNotIn('id',[1,2,3]);
        });

        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($keyword)) {
            $userQuery->where('name', 'LIKE', '%' . $keyword . '%');
            $userQuery->orWhere('email', 'LIKE', '%' . $keyword . '%');
        }

        if (!empty($university)) {
            $userQuery->where('university_id', $university);
        }

        if (!empty($state)) {
            $userQuery->where('state_id', $state);
        }

        if (!empty($city)) {
            $userQuery->where('city_id', $city);
        }

        return UserResource::collection($userQuery->orderBy('id', 'DESC')->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      // nome
      // email
      // universidade
      // senha
      // estado
      // cidade
      // plano

      $validator =  $validator = Validator::make($request->all(), [
          'name'                 => 'required|min:3',
          'email'                => 'required|email|unique:users|min:3|max:60',
          'estado'               => 'required',
          'cidade'               => 'required',
          'password'             => 'required|min:6',
          'confirmPassword'      => 'same:password',
      ],
      [
          'name.required' => 'Por favor, preencha com o nome do usuário',
          'name.min' => 'O nome do usuário deve ter no mínimo 3 caracteres',
          'email.required' => 'Por favor, preencha com o email do usuário',
          'email.email' => 'E-mail inválido',
          'email.unique' => 'E-mail já em uso por outro usuário',
          'email.min' => 'O email deve ter no mínimo 3 caracteres',
          'email.max' => 'O email deve ter no máximo 60 caracteres',
          'estado.required' => 'Por favor, selecione o estado do usuário',
          'cidade.required' => 'Por favor, selecione a cidade do usuário',
          'password.required' => 'Por favor, preencha com a senha',
          'password.min' => 'A senha deve ter no mínimo 6 caracteres',
          'password.same' => 'As senhas não coincidem',
      ]);
      if ($validator->fails()) {
          return response()->json(['errors' => 'Ops! Ocorreu um erro ao salvar o Usuário! Por favor, verifique os campos e tente novamente'], 403);
      } else {
          $params = $request->all();
          $user = User::create([
              'name'            => $params['name'],
              'email'           => $params['email'],
              'university_id'   => $params['instituicao'],
              'state_id'        => $params['estado'],
              'city_id'         => $params['cidade'],
              'password'        => Hash::make($params['password']),
          ]);
          $role = Role::findByName($params['role']);
          $user->syncRoles($role);
          $exists = \Storage::disk('local')->exists('storage_users.txt');
          if($exists){
            $contents = \Storage::disk('local')->get('storage_users.txt');
            $file = Storage::put( 'storage_users.txt', $contents."\n".'name: '.$params['name'].' | email: '.$params['email'].' | cidade: '.$params['cidade'].'/'.$params['estado'].' | senha: '.$params['password']);
          }else {
            $file = Storage::put( 'storage_users.txt', 'name: '.$params['name'].' | email: '.$params['email'].' | cidade: '.$params['cidade'].'/'.$params['estado'].' | senha: '.$params['password']);
          }
          return new UserResource($user);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */

    public function edit(){
       $id = Auth::user()->id;
       $user = User::with(['userProfile'])->findOrFail($id);

        return response()->json(['data' => $user]);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {

        // dd($request);
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        $user->update($request->all());


       //  $user->userProfile()->update([
       //      'nickname' => $request['user_profile']['nickname'],
       //      'cep' => $request['user_profile']['cep'],
       //      'address' => $request['user_profile']['address'],
       //      'number_address' => $request['user_profile']['number_address'],
       //      'cpf' => $request['user_profile']['cpf'],
       //      'rg' => $request['user_profile']['rg'],
       //      'whatsapp' => $request['user_profile']['whatsapp'],
       //      'telephone' => $request['user_profile']['telephone'],
       //      'path' => $request['user_profile']['path'],
       //      'photo' => $request['user_profile']['photo'],
       //      'biography' => $request['user_profile']['biography'],
       //      'linkedin' => $request['user_profile']['linkedin'],
       //      'facebook' => $request['user_profile']['facebook'],
       //      'instagram' => $request['user_profile']['instagram'],
       //      'twitter' => $request['user_profile']['twitter'],
       //      'youtube' => $request['user_profile']['youtube']
       //      ]
       // );


        // // dd($request->all());
        // if ($user === null) {
        //     return response()->json(['error' => 'User not found'], 404);
        // }
        // if ($user->isAdmin()) {
        //     return response()->json(['error' => 'Admin can not be modified'], 403);
        // }

        // $currentUser = Auth::user();
        // if (!$currentUser->isAdmin()
        //     && $currentUser->id !== $user->id
        //     && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_USER_MANAGE)
        // ) {
        //     return response()->json(['error' => 'Permission denied'], 403);
        // }

        // $validator = Validator::make($request->all(), $this->getValidationRules(false));
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 403);
        // } else {
        //     $email = $request->get('email');
        //     $found = User::where('email', $email)->first();
        //     if ($found && $found->id !== $user->id) {
        //         return response()->json(['error' => 'Email has been taken'], 403);
        //     }

        //     $user->name = $request->get('name');
        //     $user->email = $email;
        //     $user->save();
        //     return new UserResource($user);
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = array_map(
            function($permission) {
                return $permission['id'];
            },

            $user->getPermissionsViaRoles()->toArray()
        );

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            response()->json(['error' => 'Ehhh! Can not delete admin user'], 403);
        }

        try {
            $user->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function permissions(User $user)
    {
        try {
            return new JsonResponse([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'name' => 'required',
            'email' => $isNew ? 'required|email|unique:users' : 'required|email',
            'roles' => [
                'required',
                'array'
            ],
        ];
    }

      public function userImport(Request $request){
        $coluna1 = $request[0]['column'];
        $coluna2 = $request[1]['column'];
        $coluna3 = $request[2]['column'];
        $coluna4 = "password";
        $data1 = $request[0]['data'];
        $data2 = $request[1]['data'];
        $data3 = $request[2]['data'];
        $data4 = Hash::make('AUDIOLEGIS');

        $field = [];

        $i = 0;
        foreach($data1 as $key => $campo){
          $field[$key][$coluna1] = $campo;
          $field[$key][$coluna2] = $data2[$key];
          $field[$key][$coluna3] = $data3[$key];
          $field[$key][$coluna4] = $data4;
          $i++;
        }

      $ignoreEmpty = array_map('array_filter', $field);
      $users = User::insertOrIgnore($ignoreEmpty);

      return response()->json($users . ' cadastrados no banco');
    }
}
