<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesRequestedForCitizen extends Model
{
    //
    protected $fillable = ['name','slug','citizen_request_id'];

    public function citizenRequest(){
        return $this->belongsTo(CitizenRequest::class);
    }
    
}
