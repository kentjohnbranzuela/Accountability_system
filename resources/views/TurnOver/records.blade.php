@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary">Turn Over Records</h2>

        {{-- File Upload and Search --}}
        <form id="importForm" action="{{ route('import.turnovers') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" id="fileUpload" class="d-none" accept=".xlsx, .csv">

            <label for="fileUpload" class="custom-file-label">
                📁 <strong>Choose File</strong>
            </label>
            <span id="fileName">No file chosen</span>

            <button type="button" id="importButton" class="import-btn">
                📥 <strong>Import Excel</strong>
            </button>
        </form>

        {{-- Search --}}
        <form method="GET" action="{{ route('turnover.records') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        <style>
            .record-header {
                display: flex;
                align-items: center;
                gap: 10px;
                /* Adjust spacing */
                margin-bottom: 10px;
            }

            .import-container {
                display: flex;
                align-items: center;
                gap: 10px;
                background: #f8f9fa;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid #ddd;
                width: fit-content;
            }

            .custom-file-input {
                display: none;
            }

            .custom-file-label {
                padding: 8px 20px;
                background-color: #007bff;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                border: none;
                transition: background-color 0.2s ease;
            }

            .custom-file-label:hover {
                background-color: #0056b3;
            }

            .import-btn {
                background: #28a745;
                border: none;
                padding: 8px 20px;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                transition: background-color 0.2s ease;
            }

            .import-btn:hover {
                background: #218838;
            }

            .print-btn {
                background: #007bff;
                border: none;
                padding: 8px 20px;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                transition: background-color 0.2s ease;
            }

            .print-btn:hover {
                background-color: #0056b3;
            }

            #file-name {
                font-size: 14px;
                color: #555;
            }

            #Delete {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                background-color: #0075ea;
                /* Red color */
                border: none;
                font-weight: bold;
                border-radius: 5px;
                color: white;
                cursor: pointer;
            }

            #Delete:hover {
                background-color: rgba(220, 53, 69, 0.85);
                /* Hover effect */
                transition: background-color 0.2s ease;
            }

            /* Center the delete button below the search bar */
            .delete-container {
                display: flex;
                justify-content: left;
                /* Align to the right */
                margin-bottom: 10px;
                /* Align to the right */
            }
        </style>
    </div>

    {{-- Print Button --}}
    <button onclick="printTable()" class="btn btn-primary mb-3">🖨️ Print Table</button>
    <a href="{{ route('export.turnovers') }}" id="exportexcel" class="btn btn-primary mb-3">📤 Export to Excel</a>
    <div class="delete-container">
        <form id="deleteForm" action="{{ route('turnover.deleteAll') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" id="Delete" class="btn btn-danger">
                <i class="fa-solid fa-trash" style="margin-right: 5px;"></i> Delete All
            </button>
        </form>
    </div>
    </div>
    {{-- Turn Over Records Table --}}
    <div class="card shadow-sm" id="turnOverTable">
        <div class="card-body">
            <h4 class="mb-3 text-primary">Turn Over Records</h4>

            <div class="table-responsive">
                <table id="turnoverTable" class="table table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Serial No.</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    </thead>
                    <tbody>
                        @foreach ($turnovers as $turnover)
                            @php
                                // Check for duplicate serial number
                                $isDuplicate = \App\Models\TurnOver::where('ser_no', $turnover->ser_no)->count() > 1;
                            @endphp
                            <tr class="align-middle text-center">
                                <td>{{ $turnover->position }}</td>
                                <td>{{ $turnover->name }}</td>
                                <td>{{ $turnover->date ?? 'N/A' }}</td>
                                <td>{{ $turnover->quantity }}</td>
                                <td>{{ $turnover->description }}</td>
                                <td style="color: {{ $isDuplicate ? 'red' : 'black' }};">
                                    {{ $turnover->ser_no ?? 'N/A' }}
                                </td>
                                <td>
                                    <span
                                        class="badge
                                    {{ $turnover->status == 'Unknown' ? 'bg-secondary' : 'bg-warning' }}">
                                        {{ $turnover->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('turnover.edit', $turnover->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('turnover.destroy', $turnover->id) }}" method="POST"
                                            class="deleteForm ms-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger deleteButton">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $turnovers->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    {{-- Hidden Printable Receipt --}}
    <div id="printableReceipt" style="display: none;">
        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('2.jpg'))) }}" class="print-logo"
            style="width: 100px !important; height: 100px !important; border-radius: 50% !important; object-fit: cover !important; display: block !important;">
        <h2 style="text-align: center;">Black Line Republic</h2>
        <h3 style="text-align: center;">ACKNOWLEDGEMENT RECEIPT FOR TURNOVER EQUIPEMENT</h3>

        <p><strong>Name:</strong> {{ $turnovers->first()->name ?? '___________________________' }}</p>
        <p><strong>Position:</strong> {{ $turnovers->first()->position ?? '___________________________' }}</p>
        <p><strong>Date Received:</strong> <span id="date-received">_____________________</span></p>

        <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
            <thead>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <tr>
                    <th style="border: 1px solid black; padding: 5px;">Date</th>
                    <th style="border: 1px solid black; padding: 5px;">Quantity</th>
                    <th style="border: 1px solid black; padding: 5px;">Description</th>
                    <th style="border: 1px solid black; padding: 5px;">Serial No.</th>
                    <th style="border: 1px solid black; padding: 5px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($turnovers as $turnover)
                    <tr>
                        <td style="border: 1px solid black; padding: 5px;">{{ $turnover->date }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $turnover->quantity }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $turnover->description }}</td>
                        <td style="border: 1px solid black; padding: 5px;">
                            {{ !in_array($turnover->ser_no, ['Unknown', 'N/A']) ? $turnover->ser_no : '' }}
                        </td>
                        <td style="border: 1px solid black; padding: 5px;">
                            {{ !in_array($turnover->status, ['Unknown', 'N/A']) ? $turnover->status : '' }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Prepared by:</strong> ___________________________ (HR Officer)</p>
        <p><strong>Noted by:</strong> ___________________________ (General Manager)</p>
        <p><strong>Received by:</strong> ___________________________ <br> Signature: ___________________________</p>
    </div>
    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let exportButton = document.querySelector("#exportexcel");

            if (exportButton) {
                exportButton.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent default export behavior

                    // ✅ Ensure we check the correct table
                    let tableRows = document.querySelectorAll("#turnoverTable tbody tr");
                    console.log("Export Table Rows Count:", tableRows.length); // ✅ Debugging log

                    if (tableRows.length === 0) {
                        // ✅ If no data, show an alert and prevent export
                        Swal.fire({
                            title: "No Data to Export!",
                            text: "There are no records available to export.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                        return;
                    }

                    // ✅ Show confirmation popup before exporting
                    Swal.fire({
                        title: "Export Data?",
                        text: "Are you sure you want to export the data to Excel?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#28a745",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, Export it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log("Exporting data..."); // ✅ Debugging log
                            window.location.href = exportButton.href; // ✅ Proceed with export
                        }
                    });
                });
            } else {
                console.error("Export button not found!"); // ✅ Debugging log
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            console.log("✅ JavaScript loaded successfully!");

            let importButton = document.querySelector("#importButton");
            let fileInput = document.querySelector("#fileUpload");
            let fileNameDisplay = document.querySelector("#fileName");
            let importForm = document.querySelector("#importForm");

            // ✅ Function to update file name display
            fileInput.addEventListener("change", function() {
                if (fileInput.files.length > 0) {
                    fileNameDisplay.textContent = fileInput.files[0].name;
                } else {
                    fileNameDisplay.textContent = "No file chosen";
                }
            });

            if (importButton && fileInput && importForm) {
                importButton.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent default form submission

                    // ✅ Check if a file is selected
                    if (!fileInput.files.length) {
                        Swal.fire({
                            title: "No File Selected!",
                            text: "Please choose an Excel (.xlsx) or CSV (.csv) file before importing.",
                            icon: "warning",
                            confirmButtonText: "OK"
                        });
                        return;
                    }

                    // ✅ Validate file extension
                    let fileName = fileInput.files[0].name;
                    let allowedExtensions = /(\.xlsx|\.csv)$/i;

                    if (!allowedExtensions.exec(fileName)) {
                        Swal.fire({
                            title: "Invalid File Type!",
                            text: "Please upload an Excel (.xlsx) or CSV (.csv) file.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                        fileInput.value = ""; // Reset input
                        fileNameDisplay.textContent = "No file chosen";
                        return;
                    }

                    // ✅ Show confirmation popup before importing
                    Swal.fire({
                        title: "Import File?",
                        text: "Are you sure you want to import this file?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#28a745",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, Import it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Importing...",
                                text: "Your file is being processed.",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // ✅ Submit the form
                            importForm.submit();
                        }
                    });
                });
            } else {
                console.error("❌ Import button, file input, or form not found!");
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Select all delete buttons
            document.querySelectorAll(".deleteButton").forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent default behavior

                    let form = this.closest("form"); // Find the closest form

                    // Show confirmation alert using SweetAlert
                    Swal.fire({
                        title: "Are you sure?",
                        text: "This record will be permanently deleted!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit the form if confirmed
                        }
                    });
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            let deleteButton = document.getElementById("Delete");
            let tableBody = document.querySelector("#turnOverTable tbody"); // Adjust to your table's ID

            if (!deleteButton) {
                console.error("❌ Delete button not found!");
                return;
            }

            if (!tableBody) {
                console.error("❌ Table body not found!");
                return;
            }

            let tableRows = tableBody.querySelectorAll("tr");

            if (tableRows.length === 0) {
                deleteButton.disabled = true;
                console.log("❌ No data found, disabling delete button.");
            } else {
                deleteButton.disabled = false;
                console.log("✅ Data found, delete button enabled.");
            }

            deleteButton.addEventListener("click", function(event) {
                event.preventDefault();
                console.log("✅ Delete button clicked!");

                Swal.fire({
                    title: "Are you sure?",
                    text: "This will delete all records permanently!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log("✅ Form submitted!");
                        document.getElementById("deleteForm").submit();
                    }
                });
            });
        });

        function updateFileName() {
            const fileInput = document.getElementById('file-upload');
            const fileNameSpan = document.getElementById('file-name');
            fileNameSpan.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : "No file chosen";
        }

        function formatDate() {
            const today = new Date();
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return today.toLocaleDateString(undefined, options);
        }

        function printTable() {
            // Update the Date Received field
            document.getElementById("date-received").textContent = formatDate();

            const printContents = document.getElementById("printableReceipt").innerHTML;
            const printWindow = window.open('', '', 'width=800,height=600');

            printWindow.document.write('<html><head><title>Turn Over Records</title>');
            printWindow.document.write(
                '<style>body { font-family: Arial, sans-serif; padding: 20px; }</style></head><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection
