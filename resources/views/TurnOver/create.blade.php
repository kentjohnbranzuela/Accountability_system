@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <h2 class="text-primary fw-bold text-center mb-4">Turn-Over Entry Page</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add Technician Button -->
            <button class="btn btn-primary w-100 py-2 rounded-pill" type="button" data-bs-toggle="collapse"
                data-bs-target="#addTurnOverForm">
                + Add New Records
            </button>

            <!-- Collapsible Form -->
            <div class="collapse mt-4" id="addTurnOverForm">
                <div class="card p-4 shadow-sm border-0 rounded-3">
                    <form action="{{ route('turnover.store') }}" method="POST">
                        @csrf
                        <div id="turnOverContainer">
                            <div class="row g-3 turn-over-group">
                                <div class="col-md-4">
                                    <input type="text" name="position[]" class="form-control rounded-3 position-input" placeholder="Position" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="name[]" class="form-control rounded-3 name-input" placeholder="Name" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" name="date[]" class="form-control rounded-3 date-input" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="quantity[]" class="form-control rounded-3 quantity-input" placeholder="Quantity">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="description[]" class="form-control rounded-3 description-input" placeholder="Description">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="ser_no[]" class="form-control rounded-3 ser-no-input" placeholder="Serial No.">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="status[]" class="form-control rounded-3 status-input" placeholder="Status">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-entry">Remove</button>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-3">
                            <button type="button" id="addMoreTurnOver" class="btn btn-primary">Add More</button>
                            <button type="submit" id="saveTurnOverRecord" class="btn btn-success" disabled>Save T-O Records</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .turn-over-group {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            background: #f6f3fa;
        }
        .turn-over-group input {
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .remove-entry {
            background-color: #dc3545;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
        }
        .remove-entry:hover {
            background-color: #c82333;
        }
    </style>

    <script>
        document.getElementById('addMoreTurnOver').addEventListener('click', function() {
            let container = document.getElementById('turnOverContainer');
            let lastGroup = document.querySelector('.turn-over-group:last-of-type');
            let newGroup = lastGroup.cloneNode(true);

            // Copy values from last entry
            newGroup.querySelector('.position-input').value = lastGroup.querySelector('.position-input').value;
            newGroup.querySelector('.name-input').value = lastGroup.querySelector('.name-input').value;
            newGroup.querySelector('.date-input').value = lastGroup.querySelector('.date-input').value;
            newGroup.querySelector('.quantity-input').value = lastGroup.querySelector('.quantity-input').value;
            newGroup.querySelector('.description-input').value = lastGroup.querySelector('.description-input').value;
            newGroup.querySelector('.ser-no-input').value = lastGroup.querySelector('.ser-no-input').value;
            newGroup.querySelector('.status-input').value = lastGroup.querySelector('.status-input').value;

            newGroup.querySelector('.remove-entry').addEventListener('click', function() {
                this.closest('.turn-over-group').remove();
                checkIfEmpty();
            });

            container.appendChild(newGroup);
            checkIfEmpty();
        });

        function checkIfEmpty() {
            let container = document.getElementById('turnOverContainer');
            let saveButton = document.getElementById('saveTurnOverRecord');
            saveButton.disabled = container.querySelectorAll('.turn-over-group').length === 0;
        }

        // Enable remove button on existing items
        document.querySelectorAll('.remove-entry').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.turn-over-group').remove();
                checkIfEmpty();
            });
        });

        checkIfEmpty();
    </script>
@endsection
