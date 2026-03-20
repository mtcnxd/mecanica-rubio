<?php

namespace App\Services;

use App\Models\Client;
use App\Traits\Messenger;

class ClientService
{
    use Messenger;

    public function all()
    {
        return Client::where('status', 'Activo')->orderBy('name')->get();
    }

    public function find(string $id)
    {
        return Client::find($id);
    }

    public function create(array $data) : Client
    {
        $client = Client::where('phone', $data['phone'])->first();

        if ($client){
            throw new \Exception('El número de teléfono ya esta registrado');
        }

        $client = Client::create($data);

        $this->telegram(
            sprintf("<b>New client created:</b> %s \n\r<b>Phone:</b> %s", $data['name'], $data['phone'])
        );

        return $client;
    }

    public function update(string $id, array $data) : Client
    {
        $client = Client::find($id);

        $client->update($data);

        return $client;
    }

    public function delete(string $id) : void
    {
        $client = Client::find($id);

        $client->update([
            'status' => 'Eliminado'
        ]);
    }

    public function findByCriteria(array $criteria)
    {
        return Client::select('id','name','phone','email','status')
            ->where(function($query) use ($criteria) {
                if (isset($criteria['name'])){
                    $query->where('name','LIKE', '%'.$criteria['name'].'%');
                }

                if(isset($criteria['id'])){
                    $query->where('id', $criteria['id']);
                }
            })->get();
    }    
}