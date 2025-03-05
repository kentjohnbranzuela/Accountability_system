@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Data Entry</h2>

    <!-- Display success message -->
    @if(session('success'))

    @endif

    {{-- <!-- Search Bar -->
    <form method="GET" action="{{ route('accountability.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form> --}}

    <!-- Button to trigger Add Record Form -->
    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addRecordForm" aria-expanded="false">
        Add Record
    </button>

    <!-- Collapsible Add Record Form -->
    <div class="collapse" id="addRecordForm">
        <div class="card p-3 mb-4 shadow-sm">
            <form action="{{ route('accountability.index') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <input type="text" name="id_number" class="form-control" placeholder="ID Number" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="ser_no" class="form-control" placeholder="Serial No." required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="status" class="form-control" placeholder="Status" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">Save Record</button>
                </div>
            </form>
        </div>
    </div>

    {{-- <!-- Table to display records -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Serial No.</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $record)
                    <tr>
                        <td>{{ $record->id_number }}</td>
                        <td>{{ $record->name }}</td>
                        <td>{{ $record->date }}</td>
                        <td>{{ $record->quantity }}</td>
                        <td>{{ $record->description }}</td>
                        <td>{{ $record->ser_no }}</td>
                        <td>{{ $record->status }}</td>
                        <td>
                            <a href="{{ route('accountability.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('accountability.destroy', $record->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> --}}
@endsection
