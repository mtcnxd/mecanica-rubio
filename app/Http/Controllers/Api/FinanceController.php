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

    public function monthlyClosing(Request $request)
    {
        try {
            $response = $this->financeService->storeMonthlyClosing($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Cierre de mes exitoso',
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
