<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Notificator;
use App\Models\Expense;
use App\Models\User;

class ExpensesController extends Controller
{
    use Notificator;
    
    public function index()
    {
        $expenses = Expense::whereBetween('created_at', [now()->subDays(45), now()])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $employees = User::orderBy('name')->get();

        return view('admin.expenses.create', compact('employees'));
    }

    public function edit(string $id)
    {
        $expense = Expense::where('expenses.id', $id)
            ->first();

        return view('admin.expenses.edit', compact('expense'));
    }

    public function store(Request $request)
    {
        try {
            if($request->hasFile('attach')){
                $newFilename = time() .'.'. $request->attach->extension();
                $request->attach->move(public_path('uploads/expenses'), $newFilename);
            }

            Expense::create([
                'name'         => $request->name,
                'description'  => $request->description,
                'status'       => $request->status,
                'amount'       => $request->amount,
                'price'        => $request->price,
                'responsible'  => $request->responsible,
                'attach'       => isset($newFilename) ? $newFilename : '',
                'expense_date' => now()
            ]);

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
            if ($request->hasFile('attach')){
                $newFilename = time() .'.'. $request->attach->extension();
                $request->attach->move(public_path('uploads/expenses'), $newFilename);
    
                Expense::where('id', $id)->update([
                    "attach"       => isset($newFilename) ? $newFilename : '',
                    "expense_date" => $request->expense_date,
                    "status"       => $request->status
                ]);
    
                session()->flash('message', 'Egreso actualizado correctaamente');
    
                return to_route('admin.finance.expense.index');
            }
            
            Expense::where('id', $id)->update([
                "expense_date" => $request->expense_date,
                "status"       => $request->status
            ]);
    
            session()->flash('message', 'Egreso actualizado correctaamente');
        
        } catch (\Exception $err){
            session()->flash('warning', 'ERROR: '. $err->getMessage());
		}

        return to_route('admin.finance.expense.index');
    }

    public function deleteItem(Request $request)
    {
        DB::table('expenses')
            ->where('id', $request->id)
            ->delete();

        return response()->json([
            "success" => true,
            "message" => 'Eliminado correctamente'
        ]);
    }

    public function getImageAttached(Request $request)
    {
        return Expense::where('id', $request->id)->first();
    }
}
