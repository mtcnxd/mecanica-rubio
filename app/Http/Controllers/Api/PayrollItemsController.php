<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollRequest;
use App\Services\PayrollsService;
use Illuminate\Http\Request;

class PayrollItemsController extends Controller
{
    public function __construct(
        private PayrollsService $payrollService
    ){}

    /**
     * Store a newly created resource in storage.
     */
    public function store(PayrollRequest $request)
    {
        $this->payrollService->createItem($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Item agregado correctamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->payrollService->destroyItem($id);

            return response()->json([
                'success' => true,
                'message' => 'Item eliminado correctamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
