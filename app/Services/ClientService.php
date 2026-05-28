<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function all()
    {
        return Client::where('status', 'Activo')
            ->orderBy('name')
            ->get();
    }

    public function find(string $id): Client
    {
        return Client::findOrFail($id);
    }

    public function create(array $data): Client
    {
        $client = Client::where('phone', $data['phone'])->first();

        if ($client) {
            throw new \Exception('El número de teléfono ya esta registrado');
        }

        return Client::create($data);
    }

    public function update(string $id, array $data): Client
    {
        $client = Client::find($id);

        $client->update($data);

        return $client;
    }

    public function delete(string $id)
    {
        $client = Client::find($id);
        $client->status = 'Eliminado';
        $client->save();

        return true;
    }

    public function findByCriteria(array $criteria)
    {
        return Client::select('id', 'name', 'phone', 'email')
            ->where(function ($query) use ($criteria) {
                if (isset($criteria['name'])) {
                    $query->where('name', 'LIKE', '%'.$criteria['name'].'%');
                }
                if (isset($criteria['id'])) {
                    $query->where('id', $criteria['id']);
                }
            })->get();
    }
}
