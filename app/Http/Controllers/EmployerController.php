<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EmployerController extends Controller
{
    public function create()
    {
        Gate::authorize('create', Employer::class);
        return view('employer.create');
    }
    public function store(Request $request)
    {
        Gate::authorize('create', Employer::class);
        $validated = $request->validate([
            'company_name' => 'required|min:3|unique:employers,company_name',
        ]);

        $validated['user_id'] = auth()->id(); // Add the user_id manually

        Employer::create($validated); // Directly create the employer

        return redirect()->route('jobs.index')
            ->with('success', 'Your employer account was created!');
    }
}
