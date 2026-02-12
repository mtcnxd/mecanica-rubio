<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('status','Activo')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();

        return view('admin.users.create', compact('employees'));
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'password' => bcrypt($request->input('phone'))
            ]);

            User::create($request->except('_method','_token'));

            session()->flash(
                'success', sprintf('El usuario %s fue creado correctamente', $request->input('name'))
            );
        }

        catch (\Exception $e) {
            session()->flash(
                'success', sprintf('Error al crear usuario: %s', $e->getMessage())
            );
        }

        return to_route('users.index');
    }

    public function show(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
}