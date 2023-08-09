<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Settings::pluck('value','key')->toArray();
        if(!$settings){
            $settings = config('settings.default');
        }
        return $settings;
    }

    public function update(){
        $settings = request()->all();
        foreach ($settings as  $key => $value){
            // Settings update or create
            Settings::updateOrCreate(
                ['key'=>$key],
                ['value' =>$value]
            );
        }
        Cache::flush('settings');
        return response()->json(['success'=>'Update Successful']);

    }

}
