<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all students from the database and pass them to the view
        $students = Students::all();
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show a form to create a new student
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|integer|unique:students,nis',
            'pin' => 'required|integer',
            'balance' => 'required|numeric'
        ]);

        // Create a new student record
        Students::create($validated);

        // Redirect to the students index page with a success message
        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Students $students)
    {
        // Show details of a specific student
        return view('students.show', compact('students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Students $students)
    {
        // Show a form to edit an existing student
        return view('students.edit', compact('students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Students $students)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|integer|unique:students,nis,' . $students->id,
            'pin' => 'required|integer',
            'balance' => 'required|numeric'
        ]);

        // Update the student record
        $students->update($validated);

        // Redirect to the students index page with a success message
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Students $students)
    {
        // Delete the student record
        $students->delete();

        // Redirect to the students index page with a success message
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}

