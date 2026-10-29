<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use App\Traits\Notificator;
use App\Models\Expense;

class ExpensesController extends Controller
{
    use Notificator;

    protected $categories = [
        'Combustible',
        'Herramientas',
        'Servicio externo',
        'Servicio publico',
        'Otros',
    ];

    public function __construct(
        private FinanceService $financeService
    ){ }

    public function index()
    {
        $expenses = Expense::whereBetween('created_at', [now()->subDays(45), now()])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = $this->categories;
        $employees  = \App\Models\User::orderBy('name')->get();

        return view('admin.expenses.create', compact('employees', 'categories'));
    }

    public function edit(string $id)
    {
        try {
            $categories = $this->categories;
            $expense    = $this->financeService->getExpense($id);

            return view('admin.expenses.edit', compact('expense', 'categories'));

        } catch (\Exception $e) {
            session()->flash('warning', $e->getMessage());
            return view('error-page');
        }
    }

    public function store(ExpenseRequest $request)
    {
        dd($request);

        try {
            if($request->hasFile('attach')){
                $filename = time() .'.'. $request->attach->extension();
                $request->attach->move(public_path('uploads/expenses'), $filename);
            }

            $this->financeService->storeExpense($request->validated());

            $this->sendNotification(
                sprintf("*Expense created:* __%s__ \n*Total:* __%s__", $request->name, $request->price)
            );

            session()->flash('message', 'Egreso creado correctamente');

        } catch (\Exception $err){
            session()->flash('warning', 'ERROR: '. $err->getMessage());
		}

        return to_route('admin.finance.expense.index');
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = [
                'category'     => $request->category,
                'expense_date' => $request->expense_date,
                'status'       => $request->status,
            ];

            if ($request->hasFile('attach')){
                $filename = time() .'.'. $request->attach->extension();
                $request->attach->move(public_path('uploads/expenses'), $filename);

                $data['attach'] = $filename;
            }

            $this->financeService->updateExpense($data, $id);

            session()->flash('message', 'Egreso actualizado correctaamente');

        } catch (\Exception $err){
            session()->flash('warning', 'ERROR: '. $err->getMessage());
		}

        return to_route('admin.finance.expense.index');
    }

    public function deleteItem(Request $request): array
    {
        try {
            Expense::where('id', $request->id)->delete();
    
            return response()->json([
                "success" => true,
                "message" => 'Eliminado correctamente',
            ]);
        
        } catch (\Exception $err) {
            return response()->json([
                "success" => false,
                "message" => $er->getMessage(),
            ]);
        }
    }

    public function getImageAttached(Request $request)
    {
        return Expense::where('id', $request->id)->first();
    }
}
