<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\Telegram;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ExpensesController extends Controller
{
    public function index()
    {
        $startDate = Carbon::now()->subDays(45);
        $endDate   = Carbon::now();

        $expenses = DB::table('expenses')
            ->whereBetween('created_at', [$startDate, $endDate])
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
        $expense = DB::table('expenses')
            ->select('expenses.*', 'users.name', DB::raw('expenses.name as concept'))
            ->join('users', 'expenses.responsible','users.id')
            ->where('expenses.id', $id)
            ->first();

        return view('admin.expenses.edit', compact('expense'));
    }

    public function store(Request $request)
    {
        if($request->hasFile('attach')){
            $newFilename = time() .'.'. $request->attach->extension();
            $request->attach->move(public_path('uploads/expenses'), $newFilename);
        }

        DB::table('expenses')->insert([
            'name'         => $request->name,
            'description'  => $request->description,
            'status'       => $request->status,
            'amount'       => $request->amount,
            'price'        => $request->price,
            'responsible'  => $request->responsible,
            'attach'       => isset($newFilename) ? $newFilename : '',
            'expense_date' => Carbon::now(),
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now()
        ]);

        try {
            $telegram = new Telegram();
            $telegram->send(
                sprintf("<b>New expense created:</b> %s <b>Total:</b> $%s", $request->name, $request->price)
            );
        }
        catch (\Exception $err){
            session()->flash('warning', 'ERROR: '. $err->getMessage());
		}

        return to_route('expenses.index');
    }

    public function update(Request $request, string $id)
    {
        if ($request->hasFile('attach')){
            $newFilename = time() .'.'. $request->attach->extension();
            $request->attach->move(public_path('uploads/expenses'), $newFilename);

            Expense::where('id', $id)->update([
                "attach"       => isset($newFilename) ? $newFilename : '',
                "expense_date" => $request->expense_date,
                "status"       => $request->status,
                "updated_at"   => Carbon::now()
            ]);

            session()->flash('message', 'Egreso actualizado correctaamente');

            return to_route('expenses.index');
        }
        
        Expense::where('id', $id)->update([
            "expense_date" => $request->expense_date,
            "status"       => $request->status,
            "updated_at"   => Carbon::now()
        ]);

        session()->flash('message', 'Egreso actualizado correctaamente');

        return to_route('expenses.index');
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
        return DB::table('expenses')
            ->where('id', $request->id)
            ->first();
    }

    public function report()
    {
        $latest = DB::table('montly_balances')
            ->orderBy('close_date', 'desc')
            ->limit(2)
            ->get();

        $rows = DB::table('montly_balance_view')
            ->whereBetween('date', [Carbon::parse($latest[0]->close_date), Carbon::now()])
            ->orderBy('date')
            ->get();

        if ($latest[0]){
            $latestBalance = $latest[0]->balance;
        } else {
            $latestBalance = $latest->sum('income') - $latest[1]->expenses;
        }

        return view('admin.reports.balance', compact('rows','latest', 'latestBalance'));
    }
}
