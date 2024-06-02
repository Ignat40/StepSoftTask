@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ Auth::user()->name }}'s Information</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Bulstat</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ Auth::user()->name }}</td>
                                    <td>{{ Auth::user()->bulstat }}</td>
                                    <td>{{ Auth::user()->address }}</td>
                                    <td>{{ Auth::user()->email }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-outline-success btn-sm" id="addCounterPartyButton">Add CounterParty</button>
                    </div>

                    <form method="POST" action="{{ route('counterparties.store') }}" id="counterpartyForm" class="mt-3" style="display: none;">
                        @csrf
                        <div class="alert alert-danger d-none" id="errorMessages"></div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="bulstat" class="form-label">Bulstat</label>
                            <input type="text" class="form-control" id="bulstat" name="bulstat" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-outline-success btn-sm">Save CounterParty</button>
                    </form>

                    <div class="mt-5">
                        <h4>CounterParties</h4>
                        @foreach($counterparties as $counterparty)
                        <div class="card mb-2">
                            <div class="card-body" id="counterparty{{ $counterparty->id }}">
                                <p><strong>Name:</strong> {{ $counterparty->name }}</p>
                                <p><strong>Bulstat:</strong> {{ $counterparty->bulstat }}</p>
                                <p><strong>Address:</strong> {{ $counterparty->address }}</p>
                                <p><strong>Email:</strong> {{ $counterparty->email }}</p>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-outline-primary btn-sm edit-counterparty" data-id="{{ $counterparty->id }}">Edit</button>
                                    <button class="btn btn-outline-danger btn-sm delete-counterparty" data-id="{{ $counterparty->id }}">Delete</button>
                                </div>
                            </div>
                            <div class="card-body" id="editCounterparty{{ $counterparty->id }}" style="display: none;">
                                <form method="POST" action="{{ route('counterparties.update', $counterparty->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $counterparty->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bulstat" class="form-label">Bulstat</label>
                                        <input type="text" class="form-control" id="bulstat" name="bulstat" value="{{ $counterparty->bulstat }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ $counterparty->address }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $counterparty->email }}" required>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-outline-primary btn-sm me-2">Save</button>
                                        <button type="button" class="btn btn-outline-danger btn-sm cancel-edit" data-id="{{ $counterparty->id }}">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addCounterPartyButton').addEventListener('click', function() {
            var form = document.getElementById('counterpartyForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });

        var bulstatInput = document.getElementById('bulstat');
        bulstatInput.addEventListener('input', function() {
            var inputValue = this.value.trim();
            var isValid = /^\d{1,9}$/.test(inputValue); // Regex to check if the input consists of digits and has length between 1 and 9
            if (!isValid) {
                this.setCustomValidity('Bulstat must be a number with no more than 9 digits.');
            } else {
                this.setCustomValidity('');
            }
        });

        // Attach the submit event listener outside of the DOMContentLoaded event
        document.getElementById('counterpartyForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var formData = new FormData(this);

            // Send an AJAX request to submit the form data
            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    // Check if the response status is OK
                    if (response.ok) {
                        // Redirect to the current page to reload
                        window.location.reload();
                    } else {
                        // Handle the error
                        throw new Error('Network response was not ok');
                    }
                })
                .catch(error => {
                    // Log and handle the error
                    console.error('Error:', error);
                });
        });

        document.querySelectorAll('.edit-counterparty').forEach(function(editButton) {
            editButton.addEventListener('click', function() {
                var counterpartyId = this.getAttribute('data-id');
                var counterpartyData = document.getElementById('counterparty' + counterpartyId);
                var editCounterpartyForm = document.getElementById('editCounterparty' + counterpartyId);

                counterpartyData.style.display = 'none';
                editCounterpartyForm.style.display = 'block';
            });
        });

        document.querySelectorAll('.cancel-edit').forEach(function(cancelButton) {
            cancelButton.addEventListener('click', function() {
                var counterpartyId = this.getAttribute('data-id');
                var counterpartyData = document.getElementById('counterparty' + counterpartyId);
                var editCounterpartyForm = document.getElementById('editCounterparty' + counterpartyId);

                counterpartyData.style.display = 'block';
                editCounterpartyForm.style.display = 'none';
            });
        });

        document.querySelectorAll('.delete-counterparty').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                var counterpartyId = this.getAttribute('data-id');

                if (confirm("Are you sure you want to delete this counterparty?")) {
                    fetch('{{ route("counterparties.delete", ":id") }}'.replace(':id', counterpartyId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                var counterpartyElement = document.getElementById('counterparty' + counterpartyId);
                                if (counterpartyElement) {
                                    counterpartyElement.remove();
                                }
                            } else {
                                alert('An error occurred while deleting the counterparty.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the counterparty.');
                        });
                }
            });
        });
    });
</script>
@endsection