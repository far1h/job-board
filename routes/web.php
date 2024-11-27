 <?php

use App\Http\Controllers\JobController;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/suggestions', function () {
    $filters = request()->only('search', 'min_salary','experience','category');
    $suggestions = Job::filter($filters)->get();
    return response()->json($suggestions);
});

Route::get('', fn() => to_route('jobs.index'));
Route::resource('jobs', JobController::class);

