@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Edit Gingoog Record</h2>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    @endif

    <div class="card p-3 shadow-sm">
        <form action="{{ route('gingoogs.update', $gingoog->id) }}" method="POST" class="row g-3">            @csrf
            @method('PUT')

            <div class="col-md-3">
                <label for="id_number" class="form-label">Position</label>
                <input type="text" name="id_number" class="form-control" value="{{ $gingoog->position }}" required>
            </div>
            <div class="col-md-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $gingoog->name }}" required>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $gingoog->date }}" required>
            </div>
            <div class="col-md-2">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ $gingoog->quantity }}" required>
            </div>
            <div class="col-md-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" class="form-control" value="{{ $gingoog->description }}" required>
            </div>
            <div class="col-md-3">
                <label for="ser_no" class="form-label">Serial No.</label>
                <input type="text" name="ser_no" class="form-control" value="{{ $gingoog->ser_no }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" name="status" class="form-control" value="{{ $gingoog->status }}">
            </div>
            <div class="col-md-3">
    <button type="button" class="btn btn-success w-100" id="updateRecordBtn">Update Record</button>
</div>
        </form>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    let updateButton = document.getElementById("updateRecordBtn");

    if (updateButton) {
        updateButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent immediate form submission

            Swal.fire({
                title: "Confirm Update",
                text: "Are you sure you want to update this record?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Find and submit the closest form
                    updateButton.closest("form").submit();
                }
            });
        });
    }
});
</script>
@endsection
