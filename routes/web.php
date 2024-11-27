 <?php

use App\Http\Controllers\JobController;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('', fn() => to_route('jobs.index'));
Route::resource('jobs', JobController::class);
Route::get('/suggestions', [JobController::class, 'JobController@suggestions']);

