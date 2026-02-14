<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payroll;
use App\Models\PayrollItems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Support\MailController;
use App\Services\PayrollsService as PayrollService;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct()
    {
        $this->payrollService = new PayrollService();
    }

    public function index(Request $request)
    {
        $currentMonth = $this->payrollService->getCurrentMonth();
        return view('admin.payrolls.index', compact('currentMonth'));
    }

    public function create()
    {
        /* We plus one because current salarie is still not saved */

        $id    = Payroll::max('id') + 1;
        $items = PayrollItems::where('salary_id', $id)->get();

        return view('admin.payrolls.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->merge(['user_id' => $request->employee]);

        $this->payrollService->create($request->except('_token'));

        return to_route('payroll.index')->with('success', 'El registro se guardo correctamente');
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
    
    public function addItem(Request $request)
    {
        try {
            $id = Payroll::max('id') +1;
            
            PayrollItems::create([
                'salary_id' => $id,
                'concept'   => $request->concept,
                'amount'    => $request->amount,
            ]);
    
            return response()->json([
                'status'  => true,
                'message' => "Los datos se almacenaron correctamente"
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

    public function removeItem(Request $request)
    {       
        PayrollItems::where('id', $request->input('itemId'))->delete();

        return response()->json([
            "success" => true,
            "request" => $request->all()
        ]);
    }
}
