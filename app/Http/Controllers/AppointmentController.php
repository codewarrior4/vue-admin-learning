<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
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

    public function getStatusWithCount(){
        $cases = AppointmentStatus::cases();
 
        return collect($cases)->map( function($status){
            $count = Appointment::where('status',$status)->count();
            return [
                'name'=>$status->name,
                'value'=>$status->value,
                'color'=>$status->color(),
                'count'=>$count
            ];
        });
        
    }

    public function store(){
        request()->validate([
            'start_date'=>'required|date',
            'end_date'=>'required|date',
            'client_id'=>'required|exists:clients,id',
            'description' =>'required',
            'title' => 'required'
        ],[
            'client_id.exists'=>'The selected client does not exist',
            'client_id.required'=> 'The client field is required'
        ]);

        $appointment = Appointment::create([
            'start'=> request('start_date'),
            'end'=>request('end_date'),
            'client_id'=>request('client_id'),
            'status'=>AppointmentStatus::SCHEDULED,
            'title' =>request('title'),
            'description' =>request('description'),
        ]);

        return response()->json([
            'message'=>'Appointment created successfully',
            'appointment'=>$appointment
        ]);
    }
}
