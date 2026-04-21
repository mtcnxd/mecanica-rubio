<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Notificator;
use App\Http\Controllers\Controller;
use App\Models\BitsoData;
use App\Models\Charts;
use App\Models\Investment;
use App\Models\InvestmentData;
use App\Services\InvestmentService;
use App\Traits\Messenger;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Number;

class InvestmentsController extends Controller
{
    use Messenger;

    public function __construct(
        public InvestmentService $investmentService
    ){

    }

    public function index(Charts $charts)
    {
        $results = [
            'investments' => $this->investmentService->getActiveInvestments(),
            'bitso' => $this->investmentService->getActiveTrades(),
        ];

        // dd($results);

        return view('admin.investments.index', compact('results', 'charts'));
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
        try {
            $this->investmentService->updateInvestmentBalance($request->except('_token'));

            session()->flash('success', sprintf('El registro almacenado con exito'));
        }

        catch (\Exception $er){
            session()->flash('warning', sprintf("FAILED UPDATE DATA | MESSAGE: %s", $er->getMessage()));
        }

        return to_route('investments.index');
    }

    public function show(int $investmentId)
    {
        $investment = $this->investmentService->investmentDetails($investmentId);
        
        return view('admin.investments.show', compact('investment'));
    }

    public function destroy(Request $request)
    {
        try {
            $this->investmentService->delete($request->id);

            return response()->json([
                'success' => true,
                'type'    => 'success',
                'message' => sprintf('The element with id: %s was deleted successfully', $request->id)
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

    public function total()
    {
        $balances = $this->investmentService->getTotal();

        return response()->json([
            'total' => Number::currency($balances['total']),
            'items' => $balances['items']
        ]);
    }

    public function getActiveTrades()
    {
        return $this->investmentService->getActiveTrades();
    }
}
