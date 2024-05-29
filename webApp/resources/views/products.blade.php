@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @foreach($counterparties as $counterparty)
            <div class="card mb-4">
                <div class="card-header">{{ $counterparty->name }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $counterparty->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($counterparty->products as $product)
                                    <tr id="product-row-{{ $product->id }}">
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm edit-product" data-product-id="{{ $product->id }}">Edit</button>
                                            <button class="btn btn-outline-danger btn-sm delete-product" data-product-id="{{ $product->id }}">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-outline-success btn-sm add-product" data-id="{{ $counterparty->id }}">Add Product</button>
                    </div>

                    <form method="POST" action="{{ route('products.store') }}" class="product-form" data-counterparty="{{ $counterparty->id }}" style="display: none;">
                        @csrf
                        <input type="hidden" name="counterparty_id" value="{{ $counterparty->id }}">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_price" class="form-label">Price</label>
                            <input type="text" class="form-control" name="price" id="product_price_{{ $counterparty->id }}" required>
                            <div class="invalid-feedback" id="price-error_{{ $counterparty->id }}" style="display: none;">Please enter a valid price.</div>
                        </div>
                        <button type="submit" class="btn btn-outline-success btn-sm save-product-btn" data-counterparty="{{ $counterparty->id }}" disabled>Save Product</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.add-product').forEach(function(button) {
            button.addEventListener('click', function() {
                var counterpartyId = this.getAttribute('data-id');
                var form = document.querySelector('.product-form[data-counterparty="' + counterpartyId + '"]');
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            });
        });

        // Validate and enable save button
        document.querySelectorAll('.product-form').forEach(function(form) {
            form.addEventListener('input', function() {
                var counterpartyId = this.getAttribute('data-counterparty');
                var priceInput = document.getElementById('product_price_' + counterpartyId);
                var saveButton = document.querySelector('.save-product-btn[data-counterparty="' + counterpartyId + '"]');
                var priceError = document.getElementById('price-error_' + counterpartyId);

                // Check if the input is a valid positive number
                if (!isNaN(priceInput.value) && priceInput.value.trim() !== '' && parseFloat(priceInput.value) >= 0) {
                    saveButton.disabled = false;
                    priceError.style.display = 'none';
                } else {
                    saveButton.disabled = true;
                    priceError.style.display = 'block';
                }
            });
        });

        // Edit Product
        document.querySelectorAll('.edit-product').forEach(function(button) {
            button.addEventListener('click', function() {
                var productId = this.getAttribute('data-product-id');
                var productRow = document.querySelector('#product-row-' + productId); // Get the product row to edit

                var productNameCell = productRow.querySelector('td:nth-child(1)'); // Select the first cell for product name
                var productPriceCell = productRow.querySelector('td:nth-child(2)'); // Select the second cell for product price
                var productName = productNameCell.textContent.trim();
                var productPrice = productPriceCell.textContent.trim();

                var newName = prompt('Enter new name for the product:', productName);
                var newPrice = prompt('Enter new price for the product:', productPrice);

                // If user cancels or provides empty inputs, exit
                if (newName === null || newPrice === null || newName.trim() === '' || newPrice.trim() === '') {
                    return;
                }

                // Update UI immediately
                productNameCell.textContent = newName;
                productPriceCell.textContent = newPrice;

                // Send a request to the server to update the product details...
            });
        });



        // Delete Product
        document.querySelectorAll('.delete-product').forEach(function(button) {
            button.addEventListener('click', function() {
                var productId = this.getAttribute('data-product-id');
                var productRow = document.querySelector('#product-row-' + productId); // Get the product row to remove

                if (confirm("Are you sure you want to delete this product?")) {
                    productRow.style.display = 'none'; // Hide the product row immediately

                    fetch('{{ route("products.delete", ":id") }}'.replace(':id', productId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                // If the deletion was not successful, show the product row again
                                productRow.style.display = 'table-row';
                                throw new Error('Server response indicates failure');
                            }
                        })
                }
            });
        });



    });
</script>

@endsection