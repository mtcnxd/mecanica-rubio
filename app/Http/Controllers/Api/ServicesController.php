<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $id = $request->input('id');
            $this->orderService->markAsCompleted($id);

            return response()->json([
                'success' => true,
                'message' => 'El servicio se ha marcado como completado/pagado',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function createServicePDF(string $id)
    {
        return $this->orderService->createPDF($id);
    }
}
