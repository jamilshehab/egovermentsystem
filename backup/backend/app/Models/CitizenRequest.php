<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitizenRequest extends Model
{
    //
    protected $fillable = [
        'name',
        'email',
        'phone',
        'national_id',
        'details',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

      public function service() {
        return $this->hasOne(ServicesRequestedForCitizen::class);
    }
}
