@extends('layouts.app')

@section('content')
<div class="container">

    
    <div class="mb-3">
        <h2>Create Sale</h2>
        <form action="{{ route('sales.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="counterparty_id" class="form-label">Counterparty</label>
                <select name="counterparty_id" id="counterparty_id" class="form-select" required>
                    <option value="">Select a counterparty</option>
                    @foreach($counterparties as $counterparty)
                    <option value="{{ $counterparty->id }}">{{ $counterparty->name }}</option>
                    @endforeach
                </select>
            </div>

            
            <h4>Products</h4>
            <div class="product-item mb-3">
                <select name="products[0][product_id]" class="form-select mb-2 product-select" required>
                    <option value="">Select a product</option>
                </select>
                <input type="number" name="products[0][quantity]" class="form-control mb-2" placeholder="Quantity" required>
                <input type="number" name="products[0][unit_price]" class="form-control mb-2" placeholder="Unit Price" step="0.01" readonly>
                <input type="number" name="products[0][amount]" class="form-control mb-2" placeholder="Amount" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Create Sale</button>
        </form>
    </div>

    <div>
        <h2>List of Sales</h2>
        <ul>
            @foreach($sales as $sale)
            <li>
                Sale ID: {{ $sale->id }} - <a href="{{ route('sales.show', $sale->id) }}">View Details</a> - <a href="{{ route('sales.edit', $sale->id) }}">Edit</a>
                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('counterparty_id').addEventListener('change', function() {
            const counterpartyId = this.value;
            const productSelect = document.querySelector('.product-item .product-select');

            // Clear existing product options
            productSelect.innerHTML = '<option value="">Select a product</option>';

            if (counterpartyId) {
                fetch(`/get-products/${counterpartyId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product.id;
                            option.textContent = product.name;
                            productSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching products:', error));
            }
        });

        // Event listener for product selection
        document.querySelector('.product-select').addEventListener('change', function(event) {
            const productId = this.value;
            if (productId) {
                fetch(`/get-product-price/${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        const unitPriceInput = document.querySelector('input[name$="[unit_price]"]');
                        if (unitPriceInput) {
                            unitPriceInput.value = data.price;
                            updateAmount();
                        }
                    })
                    .catch(error => console.error('Error fetching product price:', error));
            }
        });

        // Function to update the amount field based on quantity and unit price
        function updateAmount() {
            const quantity = document.querySelector('input[name$="[quantity]"]').value;
            const unitPrice = document.querySelector('input[name$="[unit_price]"]').value;
            const amountInput = document.querySelector('input[name$="[amount]"]');
            if (quantity && unitPrice) {
                const amount = parseFloat(quantity) * parseFloat(unitPrice);
                if (!isNaN(amount)) {
                    amountInput.value = amount.toFixed(2);
                }
            }
        }

        // Update amount when quantity or unit price changes
        document.querySelectorAll('.product-item input').forEach(function(input) {
            input.addEventListener('input', function() {
                updateAmount();
            });
        });

    });
</script>


@endsection
