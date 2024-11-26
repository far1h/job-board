 <?php

use App\Http\Controllers\JobController;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/suggestions', function (Request $request) {
    $field = $request->input('field');
    $query = $request->input('query');

    if (!$query) {
        return response()->json([]);
    }

    // Validate field to avoid SQL injection risks
    if (!in_array($field, ['search', 'min_salary', 'max_salary'])) {
        return response()->json([]);
    }

    $suggestions = Job::query()
        ->when($field === 'search', function ($queryBuilder) use ($query) {
            $queryBuilder->where('title', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%');
        })
        ->when($field === 'min_salary' || $field === 'max_salary', function ($queryBuilder) use ($query) {
            $queryBuilder->where('salary', 'like', '%' . $query . '%');
        })
        ->limit(10)
        ->get(['id', $field === 'search' ? 'title as text' : 'salary as text']);

    return response()->json($suggestions);
});

Route::get('', fn() => to_route('jobs.index'));
Route::resource('jobs', JobController::class);

