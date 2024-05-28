<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Counterparty;

class CounterpartyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('counterparty.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bulstat' => 'required|string|max:9|unique:counterparties',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:counterparties',
        ]);

        Counterparty::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'bulstat' => $request->bulstat,
            'address' => $request->address,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'CounterParty added successfully.');
    }

    public function update(Request $request, Counterparty $counterparty)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'bulstat' => 'required|string|max:9',
            'address' => 'required|string',
            'email' => 'required|email',
        ]);

        // Update the counterparty's attributes
        $counterparty->update($validatedData);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Counterparty updated successfully.');

    }
}
