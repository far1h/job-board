 <?php

use App\Http\Controllers\JobController;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/suggestions', [JobController::class, 'suggestions']);
Route::get('', fn() => to_route('jobs.index'));
Route::resource('jobs', JobController::class);

