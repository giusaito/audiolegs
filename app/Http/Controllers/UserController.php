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
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($keyword)) {
            $userQuery->where('name', 'LIKE', '%' . $keyword . '%');
            $userQuery->where('email', 'LIKE', '%' . $keyword . '%');
        }

        if (!empty($university)) {
            $userQuery->where('universidade_id', $university);
        }

        return UserResource::collection($userQuery->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'password' => ['required', 'min:6'],
                    'confirmPassword' => 'same:password',
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $user = User::create([
                'name' => $params['name'],
                'email' => $params['email'],
                'password' => Hash::make($params['password']),
            ]);
            $role = Role::findByName($params['role']);
            $user->syncRoles($role);

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

        // dd($request['user']);
        $id = Auth::user()->id;
        $user = User::with(['userProfile'])->findOrFail($id);
        $user->update($request->all());


        $user->userProfile()->update([
            'nickname' => $request['user_profile']['nickname'],
            // 'state_id' => $request['user_profile']['state_id'],
            // 'state_id' => $request['user_profile']['state_id'],
            'city_id' => 18,
            'city_id' => 3994,
            'cep' => $request['user_profile']['cep'],
            'address' => $request['user_profile']['address'],
            'number_address' => $request['user_profile']['number_address'],
            'cpf' => $request['user_profile']['cpf'],
            'rg' => $request['user_profile']['rg'],
            'whatsapp' => $request['user_profile']['whatsapp'],
            'telephone' => $request['user_profile']['telephone'],
            'path' => $request['user_profile']['path'],
            'photo' => $request['user_profile']['photo'],
            'biography' => $request['user_profile']['biography'],
            'linkedin' => $request['user_profile']['linkedin'],
            'facebook' => $request['user_profile']['facebook'],
            'instagram' => $request['user_profile']['instagram'],
            'twitter' => $request['user_profile']['twitter'],
            'youtube' => $request['user_profile']['youtube']
            ]
       );


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
        foreach($request->all() as $key => $value) {
          // foreach ($value as $ket => $v) {
             $user = user::firstOrCreate([
              'name' => $value['data'][$key],
              'email' => $value['data'][$key],
              'password' => 12345,
            ]);

        }
        return 'ok';
    }
}
