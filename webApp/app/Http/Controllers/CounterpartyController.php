<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Don't forget to import the User model

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
            'name' => ['required', 'string', 'max:255'],
            'bulstat' => ['required', 'string', 'max:9', 'unique:users'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'bulstat' => $request->bulstat,
            'address' => $request->address,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('home')->with('success', 'CounterParty added successfully!');
    }
}
