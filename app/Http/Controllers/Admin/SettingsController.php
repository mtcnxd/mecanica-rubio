<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{   
    public function index()
    {
        $configs = \DB::table('settings')->get();

        return view('admin.settings.index', compact('configs'));
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if ($key != '_token' and $key != '_method'){
                \DB::table('settings')->where('name', $key)->update([
                    "value" => $value
                ]);
            }
        }

        session()->flash('success', 'La configuracion a sido guardada con exito');

        return to_route('setting.index');
    }

    public function store(Request $request)
    {
        \DB::table('settings')->insert([
            "name"  => $request->name,
            "value" => $request->value
        ]);

        session()->flash('success', 'Nuevo elemento de configuracion creado');

        return to_route('setting.index');
    }
}
