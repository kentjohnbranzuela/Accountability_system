@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary">Edit Turn Over Record</h2>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- ✅ Display success message --}}
        @if (session('success'))
            <script>
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            </script>
        @endif

        <div class="card p-3 shadow-sm">
            <form action="{{ route('turnover.update', $record->id) }}" method="POST" class="row g-3" id="updateForm">
                @csrf
                @method('PUT')

                <div class="col-md-3">
                    <label for="position" class="form-label">Position</label>
                    <input type="text" name="position" class="form-control" value="{{ $record->position }}" required>
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
                    <input type="number" name="quantity" class="form-control" value="{{ $record->quantity }}">
                </div>
                <div class="col-md-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" value="{{ $record->description }}"
                        required>
                </div>
                <div class="col-md-3">
                    <label for="ser_no" class="form-label">Serial No.</label>
                    <input type="text" name="ser_no" class="form-control" value="{{ $record->ser_no }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" name="status" class="form-control" value="{{ $record->status }}">
                </div>

                {{-- ✅ Update button with confirmation --}}
                <div class="col-md-3">
                    <button type="button" class="btn btn-success w-100" id="updateRecordBtn">Update Record</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("updateRecordBtn").addEventListener("click", function(event) {
            event.preventDefault(); // Prevent default button behavior

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update this record?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("updateForm").submit(); // Submit the form
                }
            });
        });
    </script>
@endsection
