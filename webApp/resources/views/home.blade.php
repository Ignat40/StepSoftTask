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
                        <button class="btn btn-outline-danger btn-sm me-2">Delete</button>
                        <button class="btn btn-outline-primary btn-sm">Edit</button>
                    </div>
                    <form method="POST" action="{{ route('counterparties.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="bulstat" class="form-label">Bulstat</label>
                            <input type="text" class="form-control" id="bulstat" name="bulstat">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        <button type="submit" class="btn btn-outline-success btn-sm">Add CounterParty</button>
                    </form>
                    
                    <!-- List CounterParties here -->
                    <div class="mt-5">
                        <h4>CounterParties</h4>
                        @foreach($counterparties as $counterparty)
                            <p>{{ $counterparty->name }}</p>
                            <p>{{ $counterparty->bulstat }}</p>
                            <p>{{ $counterparty->address }}</p>
                            <p>{{ $counterparty->email }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
