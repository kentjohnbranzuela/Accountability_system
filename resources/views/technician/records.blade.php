@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-primary">Technician Records</h2>

    {{-- File Upload and Search --}}
    <div class="d-flex justify-content-between mb-3">
        <div>
            <input type="file" id="fileUpload" class="form-control d-inline-block w-auto">
            <button class="btn btn-success">✔ Import Excel</button>
        </div>
        <input type="text" class="form-control w-25" placeholder="Search...">
    </div>

    {{-- Print Table Button --}}
    <button class="btn btn-primary mb-3">Print Table</button>

    {{-- Technician Records Table --}}
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Position</th>
                <th>Tech/Name</th>
                <th>Date</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Serial No</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($technicians as $technician)
                <tr>
                    <td>{{ $technician->Position }}</td>
                    <td>{{ $technician->name }}</td>
                    <td>{{ $technician->date }}</td>
                    <td>{{ $technician->quantity }}</td>
                    <td>{{ $technician->description }}</td>
                    <td>{{ !empty($technician->ser_no) ? $technician->ser_no : 'N/A' }}</td>  
                     <td>{{ $technician->status }}</td>
                    </td>
                    <td>
                        <a href="{{ route('technician.edit', $technician) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('technician.destroy', $technician) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
