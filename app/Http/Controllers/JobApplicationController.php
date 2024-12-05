<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JobApplicationController extends Controller
{
    use AuthorizesRequests;
    public function create(Job $job)
    {
        Gate::authorize('apply', $job);
        return view('job_application.create', ['job' => $job]);
    }

    public function store(Job $job, Request $request)
    {
        Gate::authorize('apply', $job);
        $job->jobApplications()->create([
            'user_id' => $request->user()->id,
            ...$request->validate([
                'expected_salary' => 'required|min:1|max:1000000'
            ])
        ]);
        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job application submitted.');
    }

    public function destroy(string $id)
    {
        //
    }
}
