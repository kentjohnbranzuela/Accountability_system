@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <h2 class="text-primary fw-bold text-center mb-4">Resign Record Entry Page</h2>

            @if (session('success'))
            @endif

            <!-- Add Record Button -->
            <button class="btn btn-primary w-100 py-2 rounded-pill" type="button" data-bs-toggle="collapse"
                data-bs-target="#addRecordForm">
                + Add New Records </button>

            <!-- Collapsible Form -->
            <div class="collapse mt-4" id="addRecordForm">
                <div class="card p-4 shadow-sm border-0 rounded-3">
                    <form action="{{ route('resign.store') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-4">
                            <input type="text" name="position" class="form-control rounded-3" placeholder="Position"
                                required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Name" required>
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="date" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="quantity" class="form-control rounded-3" placeholder="Quantity">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="description" class="form-control rounded-3"
                                placeholder="Description" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="ser_no" class="form-control rounded-3" placeholder="Serial No.">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="status" class="form-control rounded-3" placeholder="Status">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100 rounded-pill">Save Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
