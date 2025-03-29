@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary">Tools Request Records</h2>

        @if (Auth::user()->email !== 'admin@blackline.com')
            {{-- File Upload and Import Buttons --}}
            <form action="{{ route('toolsrequest.import') }}" method="POST" enctype="multipart/form-data"
                class="import-container">
                @csrf
                <input type="file" name="file" id="file-upload" class="custom-file-input" required
                    onchange="updateFileName()">
                <label for="file-upload" class="custom-file-label">
                    📁 <strong>Choose File</strong>
                </label>
                <span id="file-name">No file chosen</span>
                <button type="submit" class="import-btn">
                    📥 <strong>Import Excel</strong>
                </button>
            </form>
        @endif
        {{-- Search Bar --}}
        <form method="GET" action="{{ route('toolsrequest.records') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        <style>
            #printContainer {
                display: flex;
                align-items: center;
                gap: 5px;
                /* Adjust spacing */
                margin-bottom: 10px;
                padding: 5px;
                border-radius: 5px;
                width: fit-content;
                justify-content: space-between;
            }

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
    <div class="d-flex align-items-center" id="printContainer">
        <button onclick="printTable()" class="btn btn-primary mb-3">🖨️ Print Table</button>
        <a href="{{ route('export.toolsrequests') }}" id="exportexcel" class="btn btn-primary mb-3">📤 Export to Excel</a>
    </div>
    @if (Auth::user()->email !== 'admin@blackline.com')
        <div class="delete-container">
            <form id="deleteForm" action="{{ route('toolsrequest.deleteAll') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" id="Delete" class="btn btn-danger">
                    <i class="fa-solid fa-trash" style="margin-right: 5px;"></i> Delete All
                </button>
            </form>
        </div>
    @endif
    {{-- Tools Request Records Table --}}
    {{-- Tools Request Records Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-3 text-primary">Tools Request Records</h4>

            <button class="btn btn-primary mb-3" onclick="printSelectedTable()"><i class="fas fa-print"
                    style="margin-right: 5px;"></i> Print
                Selected</button>

            <div class="table-responsive">
                <table id="toolsrequestTable" class="table table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th> <!-- Select All Checkbox -->
                            <th>Position</th>
                            <th>Tech/Name</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Plate #</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    </thead>
                    <tbody>
                        @foreach ($toolsrequests as $toolsrequest)
                            @php
                                // Check for duplicate serial number
                                $isDuplicate =
                                    \App\Models\ToolsRequest::where('ser_no', $toolsrequest->ser_no)->count() > 1;
                            @endphp
                            <tr class="align-middle text-center">
                                <td><input type="checkbox" class="selectRow"></td> <!-- Row Checkbox -->
                                <td>{{ $toolsrequest->position }}</td>
                                <td>{{ $toolsrequest->name }}</td>
                                <td>{{ $toolsrequest->date ?? 'N/A' }}</td>
                                <td>{{ $toolsrequest->quantity }}</td>
                                <td>{{ $toolsrequest->description }}</td>
                                <td style="color: {{ $isDuplicate ? 'red' : 'black' }};">
                                    {{ $toolsrequest->ser_no ?? 'N/A' }}
                                </td>
                                <td>
                                    <span
                                        class="badge
                                    {{ $toolsrequest->status == 'NEW' ? 'bg-success' : ($toolsrequest->status == 'Unknown' ? 'bg-secondary' : 'bg-warning') }} ">
                                        {{ $toolsrequest->status }}
                                    </span>
                                </td>
                                <td>
                                    @if (Auth::user()->email !== 'admin@blackline.com')
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-warning EditToolsRequest me-1"
                                                data-id="{{ $toolsrequest->id }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('toolsrequest.destroy', $toolsrequest->id) }}"
                                                method="POST" class="deleteForm ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger deleteButton">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $toolsrequests->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    {{-- Hidden Printable Receipt --}}
    <div id="printableReceipt" style="display: none;">
        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('2.jpg'))) }}" class="print-logo"
            style="width: 100px !important; height: 100px !important; border-radius: 50% !important; object-fit: cover !important; display: block !important;">
        <h2 style="text-align: center;">Black Line Republic</h2>
        <h3 style="text-align: center;">ACKNOWLEDGEMENT RECEIPT FOR TECHNICIAN EQUIPEMENT</h3>

        <p><strong>Name:</strong> {{ $toolsrequests->first()->name ?? '___________________________' }}</p>
        <p><strong>Position:</strong> {{ $toolsrequests->first()->position ?? '___________________________' }}</p>
        <p><strong>Date Received:</strong> <span id="date-received">_____________________</span></p>

        <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
            <thead>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <tr>
                    <th style="border: 1px solid black; padding: 5px;">Date</th>
                    <th style="border: 1px solid black; padding: 5px;">Quantity</th>
                    <th style="border: 1px solid black; padding: 5px;">Description</th>
                    <th style="border: 1px solid black; padding: 5px;">Plate #.</th>
                    <th style="border: 1px solid black; padding: 5px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($toolsrequests as $toolsrequest)
                    <tr>
                        <td style="border: 1px solid black; padding: 5px;">{{ $toolsrequest->date }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $toolsrequest->quantity }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $toolsrequest->description }}</td>
                        <td style="border: 1px solid black; padding: 5px;">
                            {{ !in_array($toolsrequest->ser_no, ['Unknown', 'N/A']) ? $toolsrequest->ser_no : '' }}
                        </td>
                        <td style="border: 1px solid black; padding: 5px;">
                            {{ !in_array($toolsrequest->status, ['Unknown', 'N/A']) ? $toolsrequest->status : '' }}
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
        function printTable() {
            // Update the Date Received field
            document.getElementById("date-received").textContent = formatDate();

            const printContents = document.getElementById("printableReceipt").innerHTML;
            const printWindow = window.open('', '', 'width=800,height=600');

            printWindow.document.write('<html><head><title>Technician Records</title>');
            printWindow.document.write(
                '<style>body { font-family: Arial, sans-serif; padding: 20px; }</style></head><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.print();
        }

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
        document.addEventListener("DOMContentLoaded", function () {
    let tableRows = document.querySelectorAll("#toolsrequestTable tbody tr");

    tableRows.forEach(row => {
        row.addEventListener("click", function (event) {
            if (event.target.tagName === "INPUT" || event.target.tagName === "BUTTON" || event.target.tagName === "A" || event.target.tagName === "I") {
                return; // Prevent clicking on checkboxes, buttons, or links
            }

            let checkbox = this.querySelector(".selectRow");
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
            }
        });
    });

    // Select All Functionality
    document.getElementById("selectAll").addEventListener("change", function () {
        let checkboxes = document.querySelectorAll(".selectRow");
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});
        function printSelectedTable() {
            let selectedRows = document.querySelectorAll('.selectRow:checked');

            if (selectedRows.length === 0) {
                alert("Please select at least one record to print.");
                return;
            }

            // Get Name & Position from the first selected row
            let firstRow = selectedRows[0].closest('tr').querySelectorAll('td');
            let techName = firstRow[1].innerText.trim(); // Technician Name
            let position = firstRow[0].innerText.trim(); // Position

            let printContent = `
    <html>
    <head>
        <title>Acknowledgment Receipt for Tools Request Equipment</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 30px; }
            h2, h3 { text-align: center; }
            p { font-size: 14px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid black; padding: 8px; text-align: center; }
            th { background-color: white; color: black; }
            .signature-section { margin-top: 50px; text-align: left; }
            .signature-line { width: 40%; display: inline-block; border-top: 1px solid black; margin-top: 10px; }
            .print-logo { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; display: block; }
        </style>
    </head>
            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('2.jpg'))) }}" class="print-logo"
    <body>
        <h2>Black Line Republic</h2>
        <h3>ACKNOWLEDGEMENT RECEIPT FOR Tools Request Equipment</h3>

        <p><strong>Name:</strong> {{ $toolsrequests->first()->name ?? '___________________________' }}</p>
        <p><strong>Position:</strong> {{ $toolsrequests->first()->position ?? '___________________________' }}</p>
        <p><strong>Date Received:</strong> ${new Date().toISOString().slice(0, 10)}</p>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Plate #.</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>`;

            selectedRows.forEach(row => {
                let cells = row.closest('tr').querySelectorAll('td');

                printContent += `<tr>
            <td>${cells[3].innerText.trim()}</td>  <!-- Date -->
            <td>${cells[4].innerText.trim()}</td>  <!-- Quantity -->
            <td>${cells[5].innerText.trim()}</td>  <!-- Description -->
            <td>${cells[6].innerText.trim()}</td>  <!-- Plate #. -->
            <td>${cells[7].innerText.trim()}</td>  <!-- Status -->
        </tr>`;
            });

            printContent += `</tbody></table>

        <div class="signature-section">
            <p><strong>Prepared by:</strong> <span class="signature-line"></span> (HR Officer)</p>
            <p><strong>Noted by:</strong> <span class="signature-line"></span> (General Manager)</p>
            <p><strong>Received by:</strong> <span class="signature-line"></span></p>
        </div>

    </body></html>`;

            let printWindow = window.open('', '', 'width=900,height=700');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let deleteButton = document.getElementById("Delete");
            let tableBody = document.querySelector("#toolsrequestTable tbody"); // Adjust to your table's ID

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

        document.addEventListener("DOMContentLoaded", function() {
            let importButton = document.querySelector(".import-btn");
            let fileInput = document.querySelector("#file-upload");
            let fileNameDisplay = document.querySelector("#file-name");
            let importForm = document.querySelector(".import-container");

            // ✅ Function to update file name display
            window.updateFileName = function() {
                if (fileInput.files.length > 0) {
                    fileNameDisplay.textContent = fileInput.files[0].name;
                } else {
                    fileNameDisplay.textContent = "No file chosen";
                }
            };

            if (importButton && fileInput && importForm) {
                importButton.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent auto-submit

                    // ✅ Check if a file is selected
                    if (!fileInput.files.length) {
                        Swal.fire({
                            title: "No File Selected!",
                            text: "Please choose an Excel or CSV file before importing.",
                            icon: "warning",
                            confirmButtonText: "OK"
                        });
                        return;
                    }

                    // ✅ Validate file extension (only .xlsx and .csv allowed)
                    let fileName = fileInput.files[0].name;
                    let allowedExtensions = /(\.xls|\.xlsx|\.csv)$/i;

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
                            // ✅ Show success alert
                            Swal.fire({
                                title: "Importing...",
                                text: "Your file is being processed.",
                                icon: "success",
                                timer: 2000, // Auto-close after 2 seconds
                                showConfirmButton: false
                            });

                            // ✅ Submit the form
                            importForm.submit();
                        }
                    });
                });
            } else {
                console.error("Import button, file input, or form not found!"); // ✅ Debugging log
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            let exportButton = document.querySelector("#exportexcel");

            if (exportButton) {
                exportButton.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent immediate navigation

                    // ✅ Check if there is data in the table
                    let tableRows = document.querySelectorAll(
                        "#toolsrequestTable tbody tr"); // Ensure correct table ID
                    console.log("Export Table Rows Count:", tableRows.length); // Debugging log

                    if (tableRows.length === 0) {
                        // ✅ Show an alert and prevent export if no data
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
                        text: "Do you want to download the Excel file?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonColor: "#007bff",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, Export it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ✅ Show success alert
                            Swal.fire({
                                title: "Exporting...",
                                text: "Your Excel file is being prepared.",
                                icon: "success",
                                timer: 1500, // Auto-close after 1.5 seconds
                                showConfirmButton: false
                            });

                            // ✅ Redirect after success alert
                            setTimeout(() => {
                                window.location.href = exportButton
                                    .href; // Proceed with export
                            }, 1500);
                        }
                    });
                });
            } else {
                console.error("Export button not found!"); // ✅ Debugging log
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".deleteButton").forEach(function(button) {
                button.addEventListener("click", function() {
                    let form = this.closest(".deleteForm"); // Get the parent form

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit the form on confirmation
                        }
                    });
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            let editButtons = document.querySelectorAll(".EditToolsRequest"); // Select all Edit buttons

            editButtons.forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent default click action

                    let toolsrequestId = this.getAttribute(
                        "data-id"); // Get toolsrequest ID from data attribute

                    Swal.fire({
                        title: "Proceed to Edit?",
                        text: "Are you sure you want to edit this record?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#ffc107",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, Edit it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                `/toolsrequest/${toolsrequestId}/edit`; // Redirect if confirmed
                        }
                    });
                });
            });
        });
    </script>
    </div>
@endsection
