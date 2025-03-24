    @extends('layouts.app')

    @section('content')
        <div class="container mt-5">
            <div class="card shadow-lg border-0 rounded-4 p-4">
                <h2 class="text-primary fw-bold text-center mb-4">Tools Request Entry Page</h2>

                @if (session('success'))
                @endif

                <!-- Add Tools Request Button -->
                <button class="btn btn-primary w-100 py-2 rounded-pill" type="button" data-bs-toggle="collapse"
                    data-bs-target="#addToolsRequestForm">
                    + Add New Records
                </button>

                <!-- Collapsible Form -->
                <div class="collapse mt-4" id="addToolsRequestForm">
                    <div class="card p-4 shadow-sm border-0 rounded-3">
                        <form action="{{ route('toolsrequest.store') }}" method="POST">
                            @csrf
                            <div id="toolsRequestContainer">
                                <div class="row g-3 tools-request-group">
                                    <div class="col-md-4">
                                        <input type="text" name="position[]" class="form-control" placeholder="Position">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="name[]" class="form-control"
                                            placeholder="Technician Name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" name="date[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="quantity[]" class="form-control" placeholder="Quantity">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="description[]" class="form-control"
                                            placeholder="Description" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="ser_no[]" class="form-control" placeholder="Serial No.">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="status[]" class="form-control" placeholder="Status">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-danger remove-entry">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Wrap buttons in a div with id -->
                            <div id="buttonsContainer" class="mt-3">
                                <button type="button" id="addMore" class="btn btn-primary">Add More</button>
                                <button type="submit" id="saveTools" class="btn btn-success" disabled>Save Tools
                                    Request</button>
                            </div>
                        </form>
                    </div>
                </div>
                <style>
                    .tools-request-group {
                        border: 2px solid #ddd;
                        /* Light Gray Border */
                        border-radius: 8px;
                        padding: 15px;
                        margin-bottom: 10px;
                        /* Space between entries */
                        background: #f6f3fa;
                        /* Light background */
                    }

                    .tools-request-group input {
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
                    document.getElementById('addMore').addEventListener('click', function() {
                        let container = document.getElementById('toolsRequestContainer');
                        let lastGroup = document.querySelector('.tools-request-group:last-of-type');
                        let newGroup = lastGroup.cloneNode(true);

                        // Keep the values instead of clearing them
                        newGroup.querySelectorAll('input').forEach((input, index) => {
                            input.value = lastGroup.querySelectorAll('input')[index].value;
                        });

                        // Ensure remove button works
                        newGroup.querySelector('.remove-entry').addEventListener('click', function() {
                            this.closest('.tools-request-group').remove();
                            checkIfEmpty(); // Check entries after removal
                        });

                        container.appendChild(newGroup);
                        checkIfEmpty(); // Recheck if save button should be enabled
                    });

                    // Fix `checkIfEmpty()` to enable "Save Tools Request" correctly
                    function checkIfEmpty() {
                        let container = document.getElementById('toolsRequestContainer');
                        let saveButton = document.getElementById('saveTools');

                        // If at least one entry exists, enable the button
                        if (container.querySelectorAll('.tools-request-group').length > 0) {
                            saveButton.disabled = false;
                        } else {
                            saveButton.disabled = true;
                        }
                    }

                    // Ensure checkIfEmpty() is run initially
                    checkIfEmpty();
                </script>
            @endsection
