<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceItems;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ServicesItemsController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $serviceItems = ServiceItems::findByCriteria($request->criteria);

            return response()->json([
                'success' => true,
                'request' => $request->criteria,
                'data' => $serviceItems,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->orderService->createOrderItem($request->all());

        try {
            return response()->json([
                'success' => true,
                'request' => $request->all(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->orderService->deleteOrderItem($id);

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
