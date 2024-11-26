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
        $jobs = Job::query();
        $jobs->when(request('search'), function ($query) {
            $query->where(function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            });
        })->when(request('min_salary'), function ($query) {
            $query->where('salary', '>=', request('min_salary'));
        })->when(request('max_salary'), function ($query) {
            $query->where('salary', '<=', request('max_salary'));
        })->when(request('experience'), function ($query) {
            $query->where('experience', request('experience'));
        })->when(request('category'), function ($query) {
            $query->where('category', request('category'));
        });
        return view('job.index', ['jobs'=> $jobs->get()]);
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
        return view('job.show', compact('job'));
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
