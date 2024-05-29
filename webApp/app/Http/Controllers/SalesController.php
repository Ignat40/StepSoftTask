<?php

namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Counterparty;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::with('counterparty', 'products')->get();
        $counterparties = Counterparty::all();
        $products = Product::all();
        return view('sales', compact('sales', 'counterparties', 'products'));
    }

    public function create()
    {
        $counterparties = Counterparty::all();
        $products = Product::all();
        return view('sales.create', compact('counterparties', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bulstat' => 'required|string|max:9|unique:counterparties',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:counterparties',
        ]);
    
        Counterparty::create([
            'name' => $request->name,
            'bulstat' => $request->bulstat,
            'address' => $request->address,
            'email' => $request->email,
        ]);
    
        return redirect()->back()->with('success', 'CounterParty added successfully.');
    }


    public function edit(Sale $sale)
    {
        $counterparties = Counterparty::all();
        $products = Product::all();
        $sale->load('products');
        return view('sales.edit', compact('sale', 'counterparties', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'counterparty_id' => 'required|exists:counterparties,id',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        $sale->update([
            'counterparty_id' => $request->input('counterparty_id'),
        ]);

        $sale->products()->detach();

        foreach ($request->input('products') as $product) {
            $amount = $product['quantity'] * $product['unit_price'];
            $sale->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'amount' => $amount,
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load('counterparty', 'products');
        return view('sales.show', compact('sale'));
    }

    public function getProducts($counterpartyId)
    {
        $products = Product::where('counterparty_id', $counterpartyId)->get();
        return response()->json($products);
    }

    public function getProductPrice($productId)
    {
        $product = Product::findOrFail($productId);
        return response()->json(['price' => $product->price]);
    }
}
