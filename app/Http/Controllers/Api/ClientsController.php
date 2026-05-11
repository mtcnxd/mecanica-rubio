<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    public function __construct(
        private ClientService $clientService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $clients = $this->clientService->findByCriteria(['name' => $request->name]);

            return Response()->json([
                'success' => true,
                'data' => $clients,
                'request' => $request->name,
            ]);

        } catch (\Exception $err) {
            return Response()->json([
                'success' => false,
                'message' => $err->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getPostalCodes(Request $request)
    {
        $result = DB::table('postalcodes')
            ->where(function ($query) use ($request) {
                $query->where('postalcode', 'like', '%'.$request->postcode.'%')
                    ->where('address', 'like', '%'.$request->address.'%');
            })
            ->get();

        return Response()->json([
            'success' => false,
            'request' => $request->all(),
            'data' => $result,
        ]);
    }
}
