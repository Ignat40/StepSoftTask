@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Sale</h1>

    <!-- Counterparty Section -->
    <div class="mb-3">
        <label for="counterparty_id" class="form-label">Counterparty</label>
        <select name="counterparty_id" id="counterparty_id" class="form-select" required>
            <option value="">Select a counterparty</option>
            @foreach($counterparties as $counterparty)
                <option value="{{ $counterparty->id }}">{{ $counterparty->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Products Section -->
    <h4>Products</h4>
    <div id="product-list">
        <div class="product-item mb-3">
            <select name="products[0][product_id]" class="form-select mb-2" required>
                <option value="">Select a product</option>
            </select>
            <input type="number" name="products[0][quantity]" class="form-control mb-2" placeholder="Quantity" required>
            <input type="number" name="products[0][unit_price]" class="form-control mb-2" placeholder="Unit Price" step="0.01" required>
            <input type="number" name="products[0][amount]" class="form-control mb-2" placeholder="Amount" readonly>
        </div>
    </div>

    <button type="button" id="add-product" class="btn btn-secondary mb-3">Add Another Product</button>

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <!-- Other Form Fields Here -->

        <button type="submit" class="btn btn-primary">Create Sale</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productIndex = 1;

        document.getElementById('add-product').addEventListener('click', function() {
            const productSection = document.getElementById('product-list');
            const newProductItem = document.createElement('div');
            newProductItem.className = 'product-item mb-3';

            newProductItem.innerHTML = `
                <select name="products[${productIndex}][product_id]" class="form-select mb-2" required>
                    <option value="">Select a product</option>
                </select>
                <input type="number" name="products[${productIndex}][quantity]" class="form-control mb-2" placeholder="Quantity" required>
                <input type="number" name="products[${productIndex}][unit_price]" class="form-control mb-2" placeholder="Unit Price" step="0.01" required>
                <input type="number" name="products[${productIndex}][amount]" class="form-control mb-2" placeholder="Amount" readonly>
            `;

            productSection.appendChild(newProductItem);
            productIndex++;
        });

        document.getElementById('counterparty_id').addEventListener('change', function() {
            const counterpartyId = this.value;
            const productSelect = document.querySelector('.product-item select');

            // Clear existing options
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

        
    });
</script>
@endsection
