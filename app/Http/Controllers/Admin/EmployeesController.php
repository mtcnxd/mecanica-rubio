<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\{
    User, Employee, Payroll, PayrollItems
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VacationsController;

class EmployeesController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.employees.create', compact('users'));
    }

    public function store(Request $request)
    {        
        $status = 'Inactivo';
        if ($request->create == 'on'){
            $status = 'Activo';
        }

        User::create([
            "name"       => $request->name,
            "email"      => $request->email,
            "phone"      => $request->phone,
            "password"   => Hash::make($request->phone),
            "status"     => $status,
            "rol"        => 'Limit',
            "comments"   => $request->comments,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now()
        ]);

        DB::table('employees')
            ->insert([
                "user_id"     => User::where('email', $request->email)->first()->id,
                "salary"      => $request->salary,
                "extra"       => $request->extra,
                "periodicity" => $request->periodicity,
                "rfc"         => $request->rfc,
                "status"      => 'Activo',
                "comments"    => $request->comments,
                "created_at"  => Carbon::now(),
                "updated_at"  => Carbon::now()
            ]);        

        return to_route('employees.index')
            ->with('message', 'Los datos se guardaron correctamente');
    }

    public function show(string $id)
    {
        $employee = Employee::find($id);
        
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Request $request, string $id)
    {
        $employee = Employee::find($id);

        $extra = Carbon::parse($employee->created_at);
        
        return view('admin.employees.edit', compact('employee','extra'));
    }

    public function update(Request $request, string $id)
    {
        DB::table('employees')->where('user_id', $id)->update([
            "rfc"  => $request->rfc,
            "curp" => $request->curp,
            "nss"  => $request->nss,
        ]);

        DB::table('users')->where('id', $id)->update([
            "name"  => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
        ]);

        return to_route('employees.index')
            ->with('message', 'Los datos se actualizaron correctamente');
    }

    public function report(Request $request, Payroll $payroll)
    {
        if ($request->employee)
        {            
            $employee = Employee::find($request->employee);
            $payrolls = $payroll->where('user_id', $request->employee)->orderBy('paid_date')->get();

            return view('admin.reports.employees', compact('employee', 'payrolls'));
        }

        return view('admin.reports.employees');
    }

    public function createPendindVacationDay(Request $request)
    {
        $request->merge([
            'employee_id' => $request->employee,
            'status'      => 'Pendiente',
        ]);

        try {
            DB::table('vacations_history')->insert($request->except('employee'));
            
            DB::table('vacations_pendings')->where('employee_id', $request->employee_id)->update([
                'days_taken'   => DB::raw('days_taken + 1'),
                'days_pending' => DB::raw('days_pending - 1'),
            ]);

            return response()->json([
                'success' => true,
                'type'    => 'success',
                'message' => 'El registro se creo con exito',
            ]);
        }

        catch (Exception $e) {
            return response()->json([
                'success' => false,
                'type'    => 'error',
                'message' => 'Ocurrio un error: ' . $e->getMessage(),
            ]);
        }
    }

    public function cancellPendingVacationDay(Request $request)
    {
        $type = '';
        $message = '';

        try {
            $result = DB::table('vacations_history')->where('id', $request->id)->first();
            
            if ($result->status != 'Pendiente'){
                $type = 'warning';
                $message = sprintf('La fecha seleccionada no puede ser cancelada porque no esta en estatus pendiente');
            } 

            else if (Carbon::parse($result->date)->lt(Carbon::now())){
                $type = 'warning';
                $message = sprintf('La fecha seleccionada no puede ser cancelada porque ya es una fecha pasada');
            }

            else {
                $type = 'success';
                $message = sprintf('La fecha solicitada a sido cancelada');
                
                DB::table('vacations_history')->where('id', $request->id)->update([
                    'status' => 'Cancelado'
                ]);

                DB::table('vacations_pendings')->where('employee_id', $result->employee_id)->update([
                    'days_taken'   => DB::raw('days_taken - 1'),
                    'days_pending' => DB::raw('days_pending + 1'),
                ]);
            }

            return response()->json([
                'success' => true,
                'type'    => $type,
                'message' => $message,
                'request' => $request->id,
            ]);
        }

        catch (Exception $err){
            return response()->json([
                'success' => false,
                'type'    => 'error',
                'message' => $err->getMessage(),
            ]);
        }
    }
}
