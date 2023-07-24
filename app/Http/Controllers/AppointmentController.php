<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __invoke(){
        return view('admin.layouts.app');
    }

    public function index(){

        return Appointment::query()
            ->with('client:id,firstname,lastname')
            ->when(request('status'), function($query){
                $query->where('status',request('status'));
                //  return $query->where('status',AppointmentStatus::from(request('status')));
            })
            ->latest()
            ->paginate()
            ->through(fn ($appointment) => [
                'id'=>$appointment->id,
                'start'=>$appointment->start,
                'end'=>$appointment->end,
                'client'=>$appointment->client,
                'status' => [
                    'name' =>$appointment->status->name,
                    'desc'=>$appointment->status->name(),
                    'color'=>$appointment->status->color()
                ],
                
            ]);
    }
}
