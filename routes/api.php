<?php

use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/suggestions', [JobController::class, 'suggestions']);

