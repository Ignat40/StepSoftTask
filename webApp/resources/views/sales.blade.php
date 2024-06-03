@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">List of Sales</div>

                <div class="card-body">
                    <div class="mt-5">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sale ID</th>
                                    <th>Counterparty</th>
                                    <th>Products</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody">
                                @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->counterparty->name }}</td>
                                    <td>
                                        <ul>
                                            @foreach($sale->products as $product)
                                            <li>{{ $product->name }} (Quantity: {{ $product->pivot->quantity }}, Unit Price: ${{ $product->pivot->unit_price }}, Amount: ${{ $product->pivot->amount }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
<!--                                         <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-outline-primary btn-sm">Edit</a> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Create Sale</div>

                <div class="card-body">
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

                        <button type="submit" class="btn btn-outline-primary btn-sm">Add Sale</button>
<!--                         <button type="button" id="addRowButton" class="btn btn-outline-primary btn-sm">Add New Line</button> -->
                    </form>
                </div>
            </div>
        </div>
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

        document.getElementById('addRowButton').addEventListener('click', function() {
            const productItem = document.querySelector('.product-item');
            const newProductItem = productItem.cloneNode(true);
            const productSelect = newProductItem.querySelector('.product-select');
            const quantityInput = newProductItem.querySelector('input[name$="[quantity]"]');
            const unitPriceInput = newProductItem.querySelector('input[name$="[unit_price]"]');
            const amountInput = newProductItem.querySelector('input[name$="[amount]"]');

            // Clear input values for the new row
            productSelect.selectedIndex = 0;
            quantityInput.value = '';
            unitPriceInput.value = '';
            amountInput.value = '';

            productItem.parentNode.appendChild(newProductItem);
        });

    });

    $(document).ready(function() {
        $('#counterparty_id').select2();
        $('.product-select').select2();
    });
</script>
@endsection
