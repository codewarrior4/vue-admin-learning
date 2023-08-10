<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function updatePassword(Request $request){
        $validated = $request->validate([
            'current_password' => 'required|min:8',
            'new_password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'min:8', 'same:new_password']
        ], [
            'current_password.required' => 'The current password is required.',
            'current_password.min' => 'The current password must be at least 8 characters.',
            'current_password.same' => 'The current password is incorrect.'
        ]);
    
        // Validate the current password against the user's actual current password
        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return response()->json(['message' => 'The current password is incorrect.','errors'=>[
                "current_password" =>['The current password is incorrect.']
            ]], 422);
        }
        
        // Update the user's password with the new one
        $request->user()->update(['password' => Hash::make($validated['new_password'])]);
        
        return response()->json(['success' => 'Password updated successfully'], 200);        

    }
}
