<?php

namespace App\Models;

use App\Enums\AppointmentStatus; 
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded =[];
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'status' =>AppointmentStatus::class
    ];

    protected $appends = [
        'formattedStart',
        'formattedEnd'
    ];
    
    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function getFormattedStartAttribute(){
        return  $this->start->format('Y-m-d h:i A');
    }

    public function getFormattedEndAttribute(){
        return  $this->end->format('Y-m-d h:i A');
    }

}
