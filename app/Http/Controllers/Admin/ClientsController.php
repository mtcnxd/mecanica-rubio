<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ClientService;
use App\Traits\Messenger;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    use Messenger;

    public function __construct(
        ClientService $clientService
    ){
        $this->clientService = $clientService;
    }

    public function index()
    {
        $clients = $this->clientService->all();

        return view ('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        try {
            $client = $this->clientService->create($request->except('_method','_token'));
            session()->flash('success', sprintf('El cliente %s se guardó correctamente', $client->name));
        }
        
        catch (\Exception $e){
            session()->flash('warning', sprintf('Ocurrio un error | %s ', $e->getMessage()));
		}

        return to_route('clients.index');
    }

    public function show(string $id)
    {
        $client = $this->clientService->find($id);

        return view('admin.clients.show', compact('client'));
    }

    public function edit(string $id)
    {
        $client = $this->clientService->find($id);

        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->clientService->update($id, $request->except('_method','_token'));
            session()->flash('success', sprintf("El cliente %s se actualizo correctamente", $request->name));
        }
        
        catch (\Exception $err){
            session()->flash('warning', sprintf('Error al actualizar | %s ', $err->getMessage()));
        }

        return to_route('clients.index');
    }

    public function destroy(Request $request)
    {
        $this->clientService->delete($request->id);

        return Response()->json([
            'success' => true,
            'message' => 'El cliente se elimino correctamente'
        ]);
    }

    public function clientDetails(Request $request)
    {
        try {
            $client = $this->clientService->find($request->id);

            return Response()->json([
                'success' => true,
                'data' => $client
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getAll(Request $request)
    {
        try {
            if ($request->name || $request->id){
                $clients = $this->clientService->findByCriteria($request->all());
            } else {
                $clients = $this->clientService->all();
            }
    
            return Response()->json([
                'success' => true,
                'data'    => $clients->map(function ($client){
                    return [
                        'id'    => $client->id,
                        'name'  => $client->name,
                        'email' => $client->email,
                        'phone' => $client->phone,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Deprecates this method by search when search admites the search by postcode
    public function searchPostalCode(Request $request)
    {
        $result = \DB::table('postalcodes')
            ->where(function ($query) use ($request) {
                $query->where('postalcode', 'like', '%'.$request->postcode.'%')
                    ->where('address', 'like', '%'.$request->address.'%');
            })
            ->get();

        return Response()->json([
            "success" => true,
            "data"    => $result
        ]);
    }
}
