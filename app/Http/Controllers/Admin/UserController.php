<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Traits\HistorialTrait;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-usuario|editar-usuario|crear-usuario|eliminar-usuario', ['only' => ['index']]);
        $this->middleware('permission:crear-usuario', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-usuario', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-usuario', ['only' => ['destroy']]);
    }
    use HistorialTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $usuarios = DB::table('users as u')
                ->join('model_has_roles as mhr', 'mhr.model_id', '=', 'u.id')
                ->join('roles as r', 'mhr.role_id', '=', 'r.id')
                ->select(
                    'u.id',
                    'u.name',
                    'r.name as rol',
                    'u.email',
                    'u.status',
                );
            return DataTables::of($usuarios)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($usuarios) {
                    return view('admin.usuario.botones', compact('usuarios'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.usuario.index');
    }

    public function create()
    {
        $roles = Role::select('id', 'name')->get();

        return view('admin.usuario.create', compact('roles'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
            'status' => 'required',
        ]);
        //$input = $request->all();
        //$input['password'] = Hash::make($input['password']);
        //$user = User::create($input);
        $user =  new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = $request->status;
        $user->save();
        $user->assignRole($request->input('roles'));
        $this->crearhistorial('crear', $user->id, $user->name, null, 'usuarios');
        return redirect()->route('usuario.index');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::select('id', 'name')->get();
        $userRole = DB::table('users as ur')
            ->join('model_has_roles as mhr', 'mhr.model_id', '=', 'ur.id')
            ->where('ur.id', '=', $id)
            ->select('mhr.role_id')
            ->first();
        //$userRole = $user->roles->pluck('name', 'name')->all();

        return view('admin.usuario.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required',
            'status' => 'required',
        ]);
        $input = $request->all();



        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($input['password'])) {
            $user->password = Hash::make($input['password']);
        }
        $user->status = $request->status;
        $user->update();
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));
        $this->crearhistorial('editar', $user->id, $user->name, null, 'usuarios');
        return redirect()->route('usuario.index');
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->delete()) {
                $this->crearhistorial('eliminar', $user->id, $user->name, null, 'usuarios');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
