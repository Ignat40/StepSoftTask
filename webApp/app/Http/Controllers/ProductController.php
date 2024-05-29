<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Counterparty;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $counterparties = Counterparty::all();

        return view('products', compact('products', 'counterparties'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $counterparties = Counterparty::all();
        return view('products.create', compact('counterparties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'photo' => 'nullable|string',
            'counterparty_id' => 'required|exists:counterparties,id',
        ]);
        $product = new Product([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'photo' => $request->input('photo'),
        ]);
        $counterparty = Counterparty::findOrFail($request->input('counterparty_id'));
        $counterparty->products()->save($product);

        return redirect()->back()->with('success', 'Product added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'photo' => 'nullable|string',
        ]);

        $product->update($request->all());

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
