<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Employer::class);
    }
    public function create()
    {
        return view('employer.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|min:3|unique:employers,company_name',
        ]);

        $validated['user_id'] = auth()->id(); // Add the user_id manually

        Employer::create($validated); // Directly create the employer

        return redirect()->route('jobs.index')
            ->with('success', 'Your employer account was created!');
    }
}
