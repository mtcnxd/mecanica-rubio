<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FinanceService;
use Illuminate\Http\Request;

class ExpensesItemsController extends Controller
{
    public function __construct(
        private FinanceService $financeService
    ){ }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->financeService->deleteExpenseItem($id);

            return response()->json([
                'success' => true,
                'message' => 'Eliminado correctamente',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
