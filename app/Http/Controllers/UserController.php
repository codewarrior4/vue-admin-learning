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
        request()->validate([
            'email'=>'required|unique:users,email'
        ]);
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);
        return $user;
    }

    public function update(User $user){
        request()->validate([
            'email'=>'required|unique:users,email,'.$user->id
        ]);
        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password') ? bcrypt(request('password')) : $user->password
        ]);
        return $user;
    }
}