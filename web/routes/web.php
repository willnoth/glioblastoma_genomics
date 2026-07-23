<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::get('/patients/{patientCode}', [PatientController::class, 'showView'])->name('patients.show');
