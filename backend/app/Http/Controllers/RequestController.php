<?php

namespace App\Http\Controllers;

use App\Models\CitizenRequest;
use App\Models\ServicesRequestedForCitizen;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    //
    public function index(){
     $services=ServicesRequestedForCitizen::all();
     return response()->json(['data'=>$services]);
    }
     

     public function store(Request $request)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email',
        'phone'       => 'required|string',
        'national_id' => 'nullable|string',
        'service_id'  => 'required|exists:services_requested_for_citizens,id',
        'details'     => 'nullable|string',
    ]);

    $citizenRequest = CitizenRequest::create($validated);

    return response()->json([
        'message' => 'sent successfully',
        'data'    => $citizenRequest->load('service'),
    ]);
}
    public function destroy(){

    }
}
