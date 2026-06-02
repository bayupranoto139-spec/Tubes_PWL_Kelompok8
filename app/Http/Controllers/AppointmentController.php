<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        // Langsung arahkan ke file appointments yang ada di folder patients
        return view('patients.appointments');
    }
}