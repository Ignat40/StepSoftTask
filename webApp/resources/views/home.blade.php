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
                            <div class="card-body">
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
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-counterparty">Delete</button>
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
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });

        document.querySelectorAll('.delete-counterparty').forEach(function(button) {
            button.addEventListener('click', function() {
                // Handle delete action
            });
        });
    });
</script>
@endsection
