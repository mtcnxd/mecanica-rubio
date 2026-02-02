<?php

namespace App\Http\Controllers\Admin;

use NumberFormatter;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Services\InvestmentService;
use Illuminate\Http\Request;
use App\Models\Charts;
use App\Models\{
    InvestmentData,
    Investment,
    BitsoData
};

class InvestmentsController extends Controller
{
    public function index(BitsoData $bitsoData, Investment $investment, Charts $charts)
    {
        $bitso = $bitsoData->where('active', true)->get();

        $investments = $investment->where('active', true)->orderBy('name')->get();

        return view('admin.investments.index', compact('bitso', 'investments', 'charts'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'purchase_value' => ($request->amount * $request->price)
        ]);

        BitsoData::create($request->except('_token'));

        session()->flash('success', sprintf('Registro almacenado con exito!'));

        return to_route('investments.index');
    }

    public function update(Request $request)
    {
        $formatter = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
        
        try {
            $request->merge([
                'date'   => Carbon::now()->format('Y-m-d'),
                'amount' => $formatter->parse($request->amount),
            ]);

            InvestmentData::create($request->except('_token'));

            Investment::where('id', $request->investment_id)->update([
                'last_amount'    => Investment::raw('current_amount'),
                'current_amount' => $request->amount
            ]);
            
            session()->flash('success', sprintf('El registro almacenado con exito'));
        }

        catch (\Exception $er){
            session()->flash('warning', sprintf('we got an error: %s', $er->getMessage()));
        }

        return to_route('investments.index');
    }

    public function show(string $investment_id, Investment $investment, InvestmentData $investmentData)
    {
        $investment = $investment->find($investment_id);

        $investment = $investment->load(['investmentData' => function($query){
            $query->orderBy('date','desc')->take(13);
        }]);

        return view('admin.investments.show', compact('investment','investmentData'));
    }

    public function total()
    {
        return response()->json([
            'total' => (new InvestmentService)->getTotal()
        ]);
    }

    public function destroy(Request $request)
    {
        try {
            $result = BitsoData::find($request->id);
            $result->update([
                'active' => false
            ]);

            return response()->json([
                'success' => true,
                'type'    => 'success',
                'message' => 'Set as deleted successfully'
            ]);
        }

        catch (\Exception $er){
            return response()->json([
                'success' => false,
                'type'    => 'error',
                'message' => $er->getMessage()
            ]);
        }
    }
}
