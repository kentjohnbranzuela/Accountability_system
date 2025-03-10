@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4 p-4">
        <h2 class="text-primary fw-bold text-center mb-4">Technician Entry Page</h2>

        @if(session('success'))
        @endif

        <!-- Add Technician Button -->
        <button class="btn btn-primary w-100 py-2 rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#addTechnicianForm">
            + Add New Records
        </button>

        <!-- Collapsible Form -->
        <div class="collapse mt-4" id="addTechnicianForm">
            <div class="card p-4 shadow-sm border-0 rounded-3">
                <form action="{{ route('technician.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="Position" class="form-control rounded-3" placeholder="Position" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Technician Name" required>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="date" class="form-control rounded-3" required>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="quantity" class="form-control rounded-3" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="description" class="form-control rounded-3" placeholder="Description" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="serial_no" class="form-control rounded-3" placeholder="Serial No." required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="status" class="form-control rounded-3" placeholder="Status" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100 rounded-pill">Save Technician</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
