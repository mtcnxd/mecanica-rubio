<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function __construct(
        private EmployeeService $employeeService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = $this->employeeService->getAll();

            return response()->json([
                'success' => true,
                'data' => $employees
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function searchById(string $id)
    {
        try {
            $employees = $this->employeeService->find($id);

            return response()->json([
                'success' => true,
                'data' => $employees
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
