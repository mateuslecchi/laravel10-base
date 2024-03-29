<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::orderBy('name')->paginate(25);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $new_user = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $new_user->assignRole('Padrão');

        return to_route('usuarios.show', ['usuario' => $new_user->id])->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
        $this->authorize('view', User::class);

        return view('users.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        $this->authorize('update', User::class);

        $user_roles = $usuario->roles->pluck('name');

        $roles = Role::orderBy('name')
            ->whereNotIn('name', $user_roles)
            ->whereNotIn('name', ['Super Admin', 'Padrão', 'Relatórios - Editar'])
            ->get();

        return view('users.edit', compact('usuario', 'roles', 'user_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        $this->authorize('update', User::class);

        $request->validate([
            'password' => ['confirmed'],
        ]);

        if (! is_null($request->password)) {
            $usuario->update([
                'password' => Hash::make($request->password),
                'change_password_at' => null,
            ]);
        }

        $usuario->update([
            'name' => $request->nome,
        ]);
        if (! is_null($request->role)) {
            $usuario->syncRoles($request->role);
        } else {
            $usuario->syncRoles('Padrão');
        }

        return to_route('usuarios.show', ['usuario' => $usuario->id])->with('success', 'Usuário alterado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        $this->authorize('delete', User::class);

        $usuario->delete();

        return to_route('usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
