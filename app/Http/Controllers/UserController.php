<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __invoke(){
        return view('admin.layouts.app');
    }
    
    public function index(){ 
        $users = User::latest()->paginate(2);
        return $users;
    }

    public function store(){
        request()->validate([
            'email'=>'required|unique:users,email',
            'name'=>'required',
            'password'=>'required|min:8'
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
            'email'=>'required|unique:users,email,'.$user->id,
            'name'=>'required',
            'password'=>'sometimes|min:8'
        ]);
        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password') ? bcrypt(request('password')) : $user->password
        ]);
        return $user;
    }


    public function updateRoles(User $user){
        $user->update([
            'role' => (request('role'))
        ]);
        return response()-> json([  'success'=>true ]);

    }

    public function destroy(User $user){
        $user->delete();
        return response()->noContent();
    }

    public function userSearch(){
        $query = request('query'); 
        $users = User::where('name', 'like', "%{$query}%")->paginate(2);
        return response()->json($users);
    }
    
    public function bulkDelete(){ 
        $ids = request('ids'); 
        User::whereIn('id',$ids)->delete(); 
        return response()->json(['message'=>"User has been deleted sucessfully"]);
    }
    
}