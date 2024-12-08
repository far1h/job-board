<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use function Laravel\Prompts\search;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Job::class);
        $filters = request()->only(
            'search',
            'min_salary',
            'max_salary',
            'experience',
            'category'
        );

        return view(
            'job.index',
            ['jobs' => Job::with('employer')->latest()->filter($filters)->get()]
        );
    }

    public function suggestions(Request $request)
    {
        $field = $request->input('field');
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        // Validate field to avoid SQL injection risks
        if (!in_array($field, ['search', 'min_salary', 'max_salary'])) {
            return response()->json([]);
        }

        // Define filters for the scopeFilter
        $filters = [
            'search' => $field === 'search' ? $query : null,
        ];

        $suggestions = Job::with('employer')
            ->filter($filters)
            ->when($field === 'min_salary' || $field === 'max_salary', function ($queryBuilder) use ($query) {
                $queryBuilder->where('salary', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get(['id', $field === 'search' ? 'title as text' : 'salary as text']);

        return response()->json($suggestions);
    }

    public function show(Job $job)
    {
        Gate::authorize('view', $job);
        return view('job.show', ['job' => $job->load('employer.jobs')]);
    }
}
