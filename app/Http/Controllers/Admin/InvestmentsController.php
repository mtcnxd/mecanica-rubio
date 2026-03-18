<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

use App\Services\InvestmentService;

use App\Traits\Messenger;

use Illuminate\Http\Request;
use App\Models\Charts;
use App\Models\{
    InvestmentData,
    Investment,
    BitsoData
};

use App\Contracts\Notificator;

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

    // TODO: Deprecar
    public function total()
    {
        return response()->json([
            'total' => number_format((new InvestmentService)->getTotal(), 0)
        ]);
    }

    public function getActiveTrades()
    {
        return $this->investmentService->getActiveTrades();
    }
}
