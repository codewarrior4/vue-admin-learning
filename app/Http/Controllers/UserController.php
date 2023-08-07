<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __invoke(){
        return view('admin.layouts.app');
    }
    
    public function index(){ 
        $searchQuery = request('search');
        $users = User::query()
            ->when($searchQuery, function($query) use ($searchQuery){
                $query->where('name','LIKE',"%{$searchQuery}%")
                    ->orWhere('email','LIKE',"%{$searchQuery}%");
            })
            ->latest()
            ->paginate(3)
            ->through(fn ($user) => [
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'role'=>$user->role,
                'created_at'=>$user->created_at
            ]);
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
    
    public function bulkDelete(){ 
        $ids = request('ids'); 
        User::whereIn('id',$ids)->delete(); 
        return response()->json(['message'=>"User has been deleted sucessfully"]);
    }

    public function userStats(){
        $users = User::query()
        // Today
        ->when(request('duration') === 'TODAY', function($query){
            $query->where('created_at','=',now()->today());
        })
        // 30 days ago
        ->when(request('duration') === '30', function ($query) {
            $thirtyDaysAgo = Carbon::now()->subDays(30);
            return $query->where('created_at', '>=', $thirtyDaysAgo);
        })
        // 60 days
        ->when(request('duration') === '60', function ($query) {
            
            return $query->whereBetween('created_at',[ now()->subDays(60),now()]);
        })
        // 360 days
        ->when(request('duration') === '360', function ($query) {
            $oneYearAgo = Carbon::now()->subYear(1);
            return $query->where('created_at', '>=', $oneYearAgo);
        })
        // MTD
        ->when(request('duration') === 'MTD', function ($query) {
            return $query->whereBetween('created_at',[ now()->firstOfMonth(),now()]);
        })
        // YTD
        ->when(request('duration') === 'YTD', function ($query) {
            return $query->whereBetween('created_at',[ now()->firstOfYear(),now()]);
        })
        ->count();

        return [
            "totalUsersCount" => $users
        ];

    }
    
}