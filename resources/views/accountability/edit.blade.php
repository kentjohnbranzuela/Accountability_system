@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Edit Accountability Record</h2>

    @if(session('success'))
    @endif

    <div class="card p-3 shadow-sm">
        <form action="{{ route('accountability.update', $record->id) }}" method="POST" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-3">
                <label for="id_number" class="form-label">ID Number</label>
                <input type="text" name="id_number" class="form-control" value="{{ $record->id_number }}" required>
            </div>
            <div class="col-md-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $record->name }}" required>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $record->date }}" required>
            </div>
            <div class="col-md-2">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ $record->quantity }}" required>
            </div>
            <div class="col-md-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" class="form-control" value="{{ $record->description }}" required>
            </div>
            <div class="col-md-3">
                <label for="ser_no" class="form-label">Serial No.</label>
                <input type="text" name="ser_no" class="form-control" value="{{ $record->ser_no }}" required>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" name="status" class="form-control" value="{{ $record->status }}" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100">Update Record</button>
            </div>
        </form>
    </div>
</div>
@endsection
