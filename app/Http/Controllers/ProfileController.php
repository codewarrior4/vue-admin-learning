<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request){
        return $request->user()->only(['name','email','role']);
    }

    public function update(Request $request){
        $validated = $request->validate([
            'name' =>'required|max:25',
            'email' =>['required','email']
        ]);

        $request->user()->update($validated);

        return response()->json(['success'=>'Profile Updated successfully']);
    }
}
