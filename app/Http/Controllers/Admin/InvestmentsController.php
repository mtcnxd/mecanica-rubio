<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\Messenger;
use App\Services\ChartService;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Services\Investments\CryptoService;
use App\Services\Investments\FiatService;

class InvestmentsController extends Controller
{
    use Messenger;

    public function __construct(
        private CryptoService $cryptoService,
        private FiatService $fiatService
    ){}

    public function index(ChartService $chartsService)
    {
        try {
            $charts = $chartsService;
            $results['crypto']      = $this->cryptoService->allActive();
            $results['fiat']       = $this->fiatService->allActive();
            $results['instruments'] = $this->fiatService->allInstruments();

            return view('admin.investments.index', compact('results', 'charts'));
        }

        catch (\Exception $er){
            session()->flash('warning', $er->getMessage());

            return view('error-page');
        }
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

        return to_route('investment.index');
    }

    public function show(int $investmentId)
    {
        $investment = $this->investmentService->investmentDetails($investmentId);
        
        return view('admin.investments.show', compact('investment'));
    }
}
