<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payroll;
use App\Models\PayrollItems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Support\MailController;
use App\Services\PayrollsService as PayrollService;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\Cookie;

class PayrollController extends Controller
{
    public function __construct(
        private EmployeeService $employeeService,
        private PayrollService $payrollService
    ) { }

    public function index(Request $request)
    {
        $employee = null;

        if ($request->employee){
            $employee = $request->employee;
        }

        $payrolls = $this->payrollService->getCurrentMonth($employee);

        return view('admin.payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        try {
            $employees = $this->employeeService->getAll();
            $items     = $this->payrollService->getFormDataCreatePayroll();

            $cookieEmployee = Cookie::get('employee');
            $cookieType     = Cookie::get('type');
        
        } catch (\Exception $e){
            session()->flash('warning', 'Error | Message: '. $e->getMessage());
        }

        return view('admin.payrolls.create', compact('employees', 'items', 'cookieEmployee', 'cookieType'));
    }

    public function store(Request $request)
    {
        try {
            $this->payrollService->createPayroll($request->all());
            return to_route('admin.finance.payroll.index')->with('success', 'El registro se guardo correctamente');

        } catch (\Exception $e) {
            return to_route('admin.finance.payroll.index')->with('warning', 'Error | Message: '. $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        dd($request);
    }

    public function show(string $id)
    {
        $payroll = Payroll::find($id);
        return view('admin.payrolls.show', compact('payroll'));  
    }

    public function manageSalaries(Request $request)
    {
        $payroll = Payroll::find($request->id);
        
        switch ($request->action){
            case 'pay':
                try {
                    $payroll->update([
                        "status"     => 'Pagado',
                        "paid_date"  => now(),
                    ]);
    
                    MailController::sendPayrollEmail($payroll);

                    $message = "El pago se realizo correctamente";
                } 

                catch (\Exception $e){
                    $message = 'Error: '. $e->getMessage();
                }

            break;

            case 'cancell':
                $payroll->update([
                    'status' => 'Cancelado'
                ]);
                
                $message = "El movimiento se cancelo correctamente";
            break;

            case 'delete':
                $payroll->delete();
                $message = "El registro se elimino correctamente";
                break;
        }

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
    
    public function createItem(Request $request)
    {
        try {
            $this->payrollService->createItem($request->all());

            $items = $this->payrollService->getPayrollItems(1);
    
            return response()->json([
                'status'  => true,
                'message' => "Agregado correctamente",
                'data'    => $items,
            ]);
        }

        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);
        }
    }

    public function destroyItem(String $id)
    {
        try {
            $this->payrollService->destroyItem($id);

            return response()->json([
                "success" => true,
                "request" => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}
