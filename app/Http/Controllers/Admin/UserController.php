<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Spatie\Permission\Models\Role; 
use Illuminate\Support\Arr; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-usuario|editar-usuario|crear-usuario|eliminar-usuario', ['only' => ['index']]);
        $this->middleware('permission:crear-usuario', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-usuario', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-usuario', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
       $usuarios = User::all();

        return view('admin.usuario.index',compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::select('id','name')->get();

        return view('admin.usuario.create', compact('roles'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        return redirect()->route('usuario.index');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::select('id','name')->get();
        $userRole = DB::table('users as ur')
            ->join('model_has_roles as mhr','mhr.model_id','=','ur.id')
            ->where('ur.id', '=', $id)
            ->select( 'mhr.role_id')
            ->first();
        //$userRole = $user->roles->pluck('name', 'name')->all();

       // return $userRole;
        return view('admin.usuario.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id, 
            'roles' => 'required',
        ]);
        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));
        return redirect()->route('usuario.index');
    }
    public function destroy($id)
    {
       User::find($id)->delete();
        return redirect()->route('usuario.index');
    }
}
