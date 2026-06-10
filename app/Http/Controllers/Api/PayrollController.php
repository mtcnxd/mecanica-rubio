<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PayrollsService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct(
        private PayrollsService $payrollService
    ) { }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->payrollService->markAsPaid($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => "Nomina pagada correctamente",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
