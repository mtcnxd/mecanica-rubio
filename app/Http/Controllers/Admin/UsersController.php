<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct(
        protected UserService $userService
    ){
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAll();

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
            /**
             * User phone number is the default password
             */
            $request->merge([
                'password' => bcrypt($request->input('phone'))
            ]);

            $this->userService->storeUser($request->except('_method','_token'));

            session()->flash(
                'success', sprintf(
                    'El usuario %s fue creado correctamente, su número de teléfono es la contraseña inicial', $request->input('name')
                )
            );
        
        } catch (\Exception $e) {
            session()->flash(
                'success', sprintf('Error al crear usuario: %s', $e->getMessage())
            );
        }

        return to_route('user.index');
    }

    public function show(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $user, string $id)
    {
        try {
            $user = $this->userService->updateUser($user->validated(), $id);
            session()->flash('success', 'El contacto se modifico con exito');

        } catch (\Exception $er) {
            session()->flash('error', $er->getMessage());
        }

        return view('admin.users.edit', compact('user'));
    }
}