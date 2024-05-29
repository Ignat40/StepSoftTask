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
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Add products here -->
                                    @foreach($counterparty->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->price }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-outline-success btn-sm add-product" data-id="{{ $counterparty->id }}">Add Product</button>
                    </div>
                    <!-- Add Product Form Here -->
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
        // Toggle display of add product form
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

        // Validate and enable/disable save button
        document.querySelectorAll('.product-form').forEach(function(form) {
            form.addEventListener('input', function() {
                var counterpartyId = this.getAttribute('data-counterparty');
                var priceInput = document.getElementById('product_price_' + counterpartyId);
                var saveButton = document.querySelector('.save-product-btn[data-counterparty="' + counterpartyId + '"]');
                var priceError = document.getElementById('price-error_' + counterpartyId);

                if (!isNaN(priceInput.value) && priceInput.value.trim() !== '' && parseFloat(priceInput.value) >= 0) {
                    saveButton.disabled = false;
                    priceError.style.display = 'none';
                } else {
                    saveButton.disabled = true;
                    priceError.style.display = 'block';
                }
            });
        });
    });
</script>

@endsection
