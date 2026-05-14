<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function register(){
        return view('register');
    }

    public function store(Request $request)
    {
        $exists = Client::where('email', $request->email)->first();

        if (!$exists){
            return to_route('user.register')
                ->with('message', 'El correo que intentas registrar no existe como cliente');
        }

        User::create([
            'name'     => $request->name,
            'phone'    => '0000000000',
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => 'Activo',
            'rol'      => 'Client',
            'token'    => hash('md5',$request->name),
        ]);

        return to_route('user.register')
            ->with('message', 'Enviamos un correo para validar su cuenta');
    }

    public function login(Request $request)
    {
        sleep(random_int(1, 4));

        if (Auth::attempt(['email' => $request->username, 'password' => $request->password]))
        {
            switch (Auth::user()->rol){
                case 'Admin':
                    $request->session()->regenerate();
                    return redirect()->route('admin.service.index');

                case 'Limit':
                    $request->session()->regenerate();
                    return redirect()->route('admin.service.index');
                
                case 'Client':
                    $request->session()->regenerate();
                    return redirect()->route('clientServices.index');

                default:
                    return 'You dont have enouth permissions!';
            }
        }

        return to_route('login')
            ->with('error', 'Correo o contraseña incorrectos');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}
