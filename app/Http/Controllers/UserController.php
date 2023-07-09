<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function __invoke()
    {
        
    }
    
    public function index(){ 
        $users = User::latest()->get();
        return $users;
    }

    public function store(){
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);
        return $user;
    }
}