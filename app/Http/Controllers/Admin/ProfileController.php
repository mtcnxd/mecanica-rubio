<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);

        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        if ($request->password != $request->repeat){
            return to_route('profile.index')->with('message', 'Las contraseñas introducidas no coinciden');
        }

        $result = DB::table('users')->where('id', $request->id)->update([
            "name"     => $request->name,
            "phone"    => $request->phone,
            "password" => Hash::make($request->password)
        ]);

        return to_route('profile.index')->with('message', 'Los datos se actualizaron correctamente');
    }
}
