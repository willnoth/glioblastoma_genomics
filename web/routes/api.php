<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/patients/{patientCode}', [PatientController::class, 'showApi']);
