<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(){ 
        return Client::query()
            ->orderBy('firstname')
            ->get()
            ->map(fn ($client) => [
                'id'=>$client->id,
                'firstname'=>$client->firstname,
                'lastname'=>$client->lastname, 
            ]);
    }
}
