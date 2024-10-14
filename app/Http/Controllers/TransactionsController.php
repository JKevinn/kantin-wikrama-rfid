<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Students;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all transactions from the database and pass them to the view
        $transactions = Transactions::all();
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show a form to create a new transaction
        return view('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'status' => 'required|string|max:255'
        ]);

        // Create a new transaction record
        Transactions::create($validated);

        // Redirect to the transactions index page with a success message
        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transactions $transactions)
    {
        // Show details of a specific transaction
        return view('transactions.show', compact('transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transactions $transactions)
    {
        // Show a form to edit an existing transaction
        return view('transactions.edit', compact('transactions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transactions $transactions)
    {
        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'status' => 'required|string|max:255'
        ]);

        // Update the transaction record
        $transactions->update($validated);

        // Redirect to the transactions index page with a success message
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transactions $transactions)
    {
        // Delete the transaction record
        $transactions->delete();

        // Redirect to the transactions index page with a success message
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Transaction payment.
     */
    public function studentPayment(Request $request)
    {
        // Show a form to create a new transaction
        $student = Students::where('nis', $request->nis)->first();

        if ($student) {
            if($request->pin == $student->pin) {
                $student->update(['balance' => $student->balance - $request->amount]);

                return redirect()->route('index')->with('success', 'Payment successful.');
            } else {
                return redirect()->route('index')->with('error', 'Invalid PIN.');
            }
        }

        return redirect()->route('index')->with('error', 'Student not found.');
    }
}

