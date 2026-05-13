<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FinanceService;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function __construct(
        private FinanceService $financeService
    ) {}

    public function montlyCloseing(Request $request)
    {
        try {
            $response = $this->financeService->storeMontlyClosing($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Mes cerrado correctamente',
                'data' => $response,
            ]);
            
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }
}
