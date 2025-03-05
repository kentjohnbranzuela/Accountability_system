@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary">Accountability Records</h2>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('accountability.accountability_records') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <!-- Table to display records -->
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
                                    <a href="{{ route('accountability.edit', $record->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('accountability.destroy', $record->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
