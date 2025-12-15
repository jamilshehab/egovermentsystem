<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    //
      protected $fillable = ['citizen_request_id', 'otp_hash', 'expires_at', 'is_used', 'attempts', 'sent_via'];

       protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

     public function citizenRequest()
    {
        return $this->belongsTo(CitizenRequest::class);
    }
}
