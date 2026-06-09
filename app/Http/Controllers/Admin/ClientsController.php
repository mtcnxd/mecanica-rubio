<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Services\ClientService;
use App\Traits\Notificator;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    use Notificator;

    public function __construct(
        ClientService $clientService
    ) {
        $this->clientService = $clientService;
    }

    public function index()
    {
        $clients = $this->clientService->all();

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(ClientRequest $request)
    {
        try {
            $client = $this->clientService->create($request->validated());

            $this->sendNotification(
                sprintf("*Client created:* __%s__ \n\r*Client:* __%s__ \n\r*Phone:* __%s__", $client->id, $client->name, $client->phone)
            );

            session()->flash('success', sprintf('El cliente %s se guardó correctamente', $client->name));

        } catch (\Exception $e) {
            session()->flash('warning', sprintf('Ocurrio un error | %s ', $e->getMessage()));
        }

        return to_route('admin.client.index');
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

    public function update(ClientRequest $request, string $id)
    {
        try {
            $this->clientService->update($id, $request->validated());
            session()->flash('success', sprintf('El cliente %s se actualizo correctamente', $request->name));

        } catch (\Exception $err) {
            session()->flash('warning', sprintf('Error al actualizar | %s ', $err->getMessage()));
        }

        return to_route('admin.client.index');
    }

    public function destroy(string $id)
    {
        try {
            $this->clientService->delete($id);

            return Response()->json([
                'success' => true,
                'message' => 'El cliente se elimino correctamente',
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function clientDetails(Request $request)
    {
        try {
            $client = $this->clientService->find($request->id);

            return Response()->json([
                'success' => true,
                'data' => $client,
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get all the clients (For WEB and API requests)
     *
     * @return JsonResponse
     */
    public function getAll(Request $request)
    {
        try {
            if ($request->name || $request->id) {
                $clients = $this->clientService->findByCriteria($request->all());
            } else {
                $clients = $this->clientService->all();
            }

            return Response()->json([
                'success' => true,
                'data' => $clients->map(function ($client) {
                    return [
                        'id' => $client->id,
                        'name' => $client->name,
                        'email' => $client->email,
                        'phone' => $client->phone,
                    ];
                }),
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
