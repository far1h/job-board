<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only(
            'search',
            'min_salary',
            'max_salary',
            'experience',
            'category'
        );

        return view(
            'job.index',
            ['jobs' => Job::with('employer')->filter($filters)->get()]
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('job.show', ['job' => $job->load('employer.jobs')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
