<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InvestmentService;
use App\Services\Investments\CryptoService;
use App\Services\Investments\FiatService;
use Illuminate\Http\Request;

class InvestmentsController extends Controller
{
    public function __construct(
        private FiatService $fiatService,
        private CryptoService $cryptoService
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->cryptoService->dataStore($request->all());

            return response()->json([
                'success' => true,
                'message' => $request->all()
            ]);

        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $this->cryptoService->destroy($request->id);

            return response()->json([
                'success' => true,
                'message' => sprintf('The element with id: %s was deleted successfully', $request->id),
                'type'    => 'success',
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
