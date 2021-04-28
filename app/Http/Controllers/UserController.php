<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('users.index')->with([ 'users_count' => User::count() ]);
        }
        $users = User::filterUsers()->orderBy('name', 'ASC')->paginate();
        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        // ASSIGN CUSTOMER
        $user->customer()->associate(Customer::find($validated['customer_id']));
        $user->save();

        $user->syncRoles($validated['role_id']);

        return $user
            ? response()->json([ "msg" => "Usuario creado correctamente."], 200)
            : response()->json([ "msg" => "No se ha podido crear el usuario, por favor, contacte con el administrador."], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user): View
    {
        return view('users.edit')->with([ 'user' => $user ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UserRequest  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        $user->update([
            'mtcdr_customer_name' => $validated['mtcdr_customer_name'],
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
            'is_active' => $validated['is_active'] ? true : false,
        ]);

        if(isset($validated['customer_id'])) {
            $user->customer()->associate(Customer::find( $validated['customer_id'] ));
        }
        if(isset($validated['department_id'])) {
            $user->department()->associate(Department::find( $validated['department_id'] ));
        }
        if(isset($validated['role_id'])) {
            $user->syncRoles(Role::find($validated['role_id']));
        }
        $user->save();

        return response()->json([ "msg" => "Usuario actualizado correctamente"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // if(auth()->user()->can('destroy', User::class)) {
            $user->update([ 'deleted_by' => auth()->user()->id ]);
            $deleted = $user->delete();
        // }

        return isset($deleted) && $deleted
            ? response()->json([ "msg" => __("Usuario eliminado correctamente.")], 200)
            : response()->json([ "msg" => __("El usuario no ha podido ser eliminado, por favor, contacte con su administrador")], 400);
        
    }

    public function get_all_agents(): JsonResponse
    {
        return response()->json(['agents' => User::role([1, 2])->get()->toArray() ], 200);
    }

    public function get_customer_users(Customer $customer): JsonResponse
    {
        return response()->json(['users' => $customer->users()->get()->toArray() ], 200);
    }

    public function profile(User $user): View
    {
        return view('profiles.index')->with([ 'user' => $user->load('customer.users') ]);
    }
}
