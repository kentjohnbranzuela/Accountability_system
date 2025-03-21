    @extends('layouts.app')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @section('content')
        <div class="container mt-4">
            <h2 class="text-primary">AWOL Records</h2>

            {{-- File Upload and Search --}}
            <form id="ImportForm" action="{{ route('awol.import') }}" method="POST" enctype="multipart/form-data"
                class="import-container">
                @csrf
                <input type="file" name="file" id="file-upload" class="custom-file-input" required
                    onchange="updateFileName()">
                <label for="file-upload" class="custom-file-label">📂 Choose File</label>
                <span id="file-name">No file chosen</span>
                <button type="button" id="Import" class="import-btn">📥 Import Excel</button>
            </form>
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('awol.records') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>

            {{-- Print and Export Buttons --}}
            <button onclick="printTable()" class="btn btn-primary mb-3">🖨️ Print Table</button>
            <a href="{{ route('export.awol') }}" class="btn btn-primary mb-3" id="exportExcel">📤 Export to Excel</a>

            {{-- Delete All Button --}}
            <div class="delete-container">
                <form id="deleteForm" action="{{ route('awol.deleteAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="Delete" class="btn btn-danger d-flex align-items-center gap-1">
                        <i class="fa-solid fa-trash"></i> Delete All
                    </button>
                </form>
            </div>
            <!-- Visible Table -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3 text-primary">AWOL Records</h4>

                    <div class="table-responsive">
                        <table id="awolTable" class="table table-striped table-hover align-middle">
                            <thead class="table-dark text-center">
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            </thead>
                            <tbody>
                                @foreach ($awols as $awol)
                                    @php
                                        // Check if serial number is duplicate
                                        $isDuplicate =
                                            \App\Models\AwolRecord::where('ser_no', $awol->ser_no)->count() > 1;
                                    @endphp

                                    <tr class="text-center">
                                        <td>{{ $awol->position }}</td>
                                        <td>{{ $awol->name }}</td>
                                        <td>{{ $awol->date ?? 'N/A' }}</td>
                                        <td>{{ $awol->quantity }}</td>
                                        <td>{{ $awol->description }}</td>
                                        <td style="color: {{ $isDuplicate ? 'red' : 'black' }};">
                                            {{ $awol->ser_no ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge
                                    {{ $awol->status == 'NEW' ? 'bg-success' : ($awol->status == 'Unknown' ? 'bg-secondary' : 'bg-warning') }}">
                                                {{ $awol->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('awol.edit', $awol->id) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('awol.destroy', $awol->id) }}" method="POST"
                                                    class="deleteForm d-inline-block m-0">
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

                    <div class="d-flex justify-content-end mt-3">
                        {{ $awols->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Hidden Printable Receipt -->
        <div id="printableReceipt" style="display: none;">
            <!-- Logo -->
            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('2.jpg'))) }}"
                class="print-logo"
                style="width: 100px !important; height: 100px !important; border-radius: 50% !important; object-fit: cover !important; display: block !important;">

            <!-- Title -->
            <h2 style="text-align: center;">Black Line Republic</h2>
            <h3 style="text-align: center;">ACKNOWLEDGEMENT RECEIPT FOR AWOL EQUIPEMENT</h3>

            <!-- Dynamic Data -->
            <p><strong>Name:</strong> {{ $awols->first()->name ?? '___________________________' }}</p>
            <p><strong>Position:</strong> {{ $awols->first()->position ?? '___________________________' }}</p>
            <p><strong>Date Received:</strong> <span id="date-received">_____________________</span></p>

            <!-- Table -->
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px;">Date</th>
                        <th style="border: 1px solid black; padding: 5px;">Quantity</th>
                        <th style="border: 1px solid black; padding: 5px;">Description</th>
                        <th style="border: 1px solid black; padding: 5px;">Serial No.</th>
                        <th style="border: 1px solid black; padding: 5px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($awols as $awol)
                        <tr>
                            <td style="border: 1px solid black; padding: 5px;">{{ $awol->date }}</td>
                            <td style="border: 1px solid black; padding: 5px;">{{ $awol->quantity }}</td>
                            <td style="border: 1px solid black; padding: 5px;">{{ $awol->description }}</td>
                            <td style="border: 1px solid black; padding: 5px;">
                                {{ !in_array($awol->ser_no, ['Unknown', 'N/A']) ? $awol->ser_no : '' }}
                            </td>
                            <td style="border: 1px solid black; padding: 5px;">
                                {{ !in_array($awol->status, ['Unknown', 'N/A']) ? $awol->status : '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Signatures -->
            <p><strong>Prepared by:</strong> ___________________________ (HR Officer)</p>
            <p><strong>Noted by:</strong> ___________________________ (General Manager)</p>
            <p><strong>Received by:</strong> ___________________________ <br> Signature: ___________________________</p>
        </div>

        <script>
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

                var printContents = document.getElementById("printableReceipt").innerHTML;
                var printWindow = window.open('', '', 'width=800,height=600');

                printWindow.document.write('<html><head><title>AWOL Records</title>');
                printWindow.document.write(
                    '<style>body { font-family: Arial, sans-serif; padding: 20px; }</style></head><body>');
                printWindow.document.write(printContents);
                printWindow.document.write('</body></html>');

                printWindow.document.close();
                printWindow.print();
            }
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let deleteButton = document.getElementById("Delete");
                let tableBody = document.querySelector("#awolTable tbody"); // Adjust to your table's ID

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
                console.log("JavaScript loaded successfully!"); // ✅ Debugging log

                let importButton = document.querySelector("#Import");
                let fileInput = document.querySelector("#file-upload");
                let fileNameDisplay = document.querySelector("#file-name");
                let importForm = document.querySelector("#ImportForm");

                // ✅ Function to update file name display
                window.updateFileName = function() {
                    if (fileInput.files.length > 0) {
                        let fileName = fileInput.files[0].name;
                        fileNameDisplay.textContent = fileName;
                    } else {
                        fileNameDisplay.textContent = "No file chosen";
                    }
                };

                if (importButton && fileInput && importForm) {
                    importButton.addEventListener("click", function(event) {
                        event.preventDefault(); // ✅ Prevent default form submission

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

                        // ✅ Validate file extension (only .xls, .xlsx, .csv)
                        let fileName = fileInput.files[0].name;
                        let allowedExtensions = /(\.xls|\.xlsx|\.csv)$/i;

                        if (!allowedExtensions.exec(fileName)) {
                            Swal.fire({
                                title: "Invalid File Type!",
                                text: "Please upload an Excel (.xls, .xlsx) or CSV (.csv) file.",
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
                let exportButton = document.querySelector("#exportExcel");

                if (exportButton) {
                    exportButton.addEventListener("click", function(event) {
                        event.preventDefault(); // Prevent default export

                        // ✅ Count table rows dynamically for #awolTable
                        let tableRows = document.querySelectorAll("#awolTable tbody tr");
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
                }
            });
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".deleteButton").forEach(function(button) {
                    button.addEventListener("click", function(event) {
                        event.preventDefault(); // Prevent default form submission
                        let form = this.closest("form"); // Find the closest form

                        Swal.fire({
                            title: "Are you sure?",
                            text: "Once deleted, you cannot recover this record!",
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
        </script>

        <style>
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

            .custom-file-label {
                padding: 8px 20px;
                background-color: #007bff;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
            }

            .custom-file-label:hover {
                background-color: #0056b3;
            }

            .custom-file-input {
                display: none;
            }

            #importContainer {
                display: flex;
                align-items: center;
                gap: 10px;
                /* Adjust spacing */
                padding: 5px;
                border-radius: 5px;
                width: fit-content;
                justify-content: space-between;
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
    @endsection
