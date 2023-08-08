<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Settings::pluck('value','key')->toArray();
    }

    public function update(){
        $settings = request()->all();
        foreach ($settings as  $key => $value){
            Settings::where('key',$key)->update(['value' => $value]);
        }
        return response()->json(['success'=>'Update Successful']);
    }

}
