    @extends('layouts.app')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @section('content')

        <div class="container mt-4">
            <h2 class="text-primary">BRO Records</h2>
            <form action="{{ route('accountability.import') }}" method="POST" enctype="multipart/form-data" class="import-container">
        @csrf
        <input type="file" name="file" id="file-upload" class="custom-file-input" required onchange="updateFileName()">
        <label for="file-upload" class="custom-file-label">📂 Choose File</label>
        <span id="file-name">No file chosen</span>
        <button type="submit" class="import-btn">📥 Import Excel</button>
    </form>
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

        .custom-file-input {
            display: none;
        }

        .custom-file-label {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .import-btn {
            background: #28a745;
            border: none;
            padding: 8px 15px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .import-btn:hover {
            background: #218838;
        }

        #file-name {
            font-size: 14px;
            color: #555;
        }
        .duplicate {
        color: red;
        fon
        t-weight: bold;
    }
    .print-logo {
        width: 100px; /* Adjust size as needed */
        height: 100px; /* Ensure width and height are equal for a perfect circle */
        border-radius: 50%; /* Makes it round */
        object-fit: cover; /* Ensures the image maintains its aspect ratio */
        border: 2px solid #ccc; /* Optional: Add a border around the logo */
    }
    #Delete {
    margin-top: 10px; /* Space between search bar and delete button */
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    background-color: #0075ea; /* Red color */
    border: none;
    font-weight: bold;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}

#Delete:hover {
    background-color: rgba(220, 53, 69, 0.85); /* Hover effect */
    transition: background-color 0.2s ease;
}

/* Center the delete button below the search bar */
.delete-container {
    display: flex;
    justify-content: right; /* Align to the right */
    margin-top: 10px; /* Add some spacing */
}

    </style>

    <script>
        function updateFileName() {
            const fileInput = document.getElementById('file-upload');
            const fileNameSpan = document.getElementById('file-name');
            fileNameSpan.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : "No file chosen";
        }
    </script>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('accountability.accountability_records') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
            <!-- Print Button -->
            <button onclick="printTable()" class="btn btn-primary">🖨️ Print Table</button>
            <a href="{{ route('export.BRO') }}" id="exportExcel" class="btn btn-primary">📤 Export to Excel</a>

            <div class="mt-2 text-center">
    <form id="deleteform" action="{{ route('bro.deleteAll') }}" method="POST">
        @csrf
        @method('DELETE')
<button type="button" id="Delete" class="btn btn-danger d-flex align-items-center gap-1">
        <i class="fa-solid fa-trash"></i> Delete All
    </button>    </form>
</div>
          <!-- Visible Table -->
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="mb-3 text-primary">BRO Records</h4>

        <div class="table-responsive">
            <table id="accountabilityTable" class="table table-striped table-hover align-middle">
                <thead class="table-dark text-center">
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th style="width: 0%;">Description</th>
                            <th>Ser_No</th>
                            <th>Status</th>
                            <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $record)
                        @php
                            // Check if serial number is duplicate
                            $isDuplicate = \App\Models\AccountabilityRecord::where('ser_no', $record->ser_no)->count() > 1;
                        @endphp

                        <tr class="text-center">
                            <td>{{ $record->id_number }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ $record->date ?? 'N/A' }}</td>
                            <td>{{ $record->quantity }}</td>
                            <td class="text-start">{{ $record->description }}</td>

                            <!-- Highlight Serial Number in Red if Duplicate -->
                            <td style="color: {{ $isDuplicate ? 'red' : 'black' }};">
                                {{ $record->ser_no ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="badge
                                    {{ $record->status == 'NEW' ? 'bg-success' : ($record->status == 'Unknown' ? 'bg-secondary' : 'bg-warning') }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('accountability.edit', $record->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('accountability.destroy', $record->id) }}" method="POST" class="deleteForm d-inline-block m-0">
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
            {{ $records->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>


            <!-- Hidden Printable Receipt -->
            <div id="printableReceipt" style="display: none;">
            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('2.jpg'))) }}" class="print-logo"
            style="width: 100px !important; height: 100px !important; border-radius: 50% !important; object-fit: cover !important; display: block !important;">            <h2 style="text-align: center;">Black Line Republic</h2>
        <h3 style="text-align: center;">ACKNOWLEDGEMENT RECEIPT FOR BRO EQUIPMENT</h3>

        <!-- Dynamically insert the first name & position (ID Number) from the records -->
        <p><strong>Name:</strong> {{ $records->first()->name ?? '___________________________' }}</p>
        <p><strong>Position:</strong> {{ $records->first()->id_number ?? '___________________________' }}</p>
        <p><strong>Date Received:</strong> <span id="date-received">_____________________</span></p>

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
                @foreach ($records as $record)
                    <tr>
                        <td style="border: 1px solid black; padding: 5px;">{{ $record->date }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $record->quantity }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $record->description }}</td>
                        <td style="border: 1px solid black; padding: 5px;">
        {{ $record->ser_no !== ['Unknown','N/A'] ? $record->ser_no : '' }}
    </td>                        <td style="border: 1px solid black; padding: 5px;">
    {{ !in_array($record->status, ['Unknown', 'N/A']) ? $record->status : '' }}
</td>
                    </tr>
                    @php
        // Count how many times this serial number appears
        $isDuplicate = \App\Models\AccountabilityRecord::where('ser_no', $record->ser_no)->count() > 1;
    @endphp

        @endforeach
            </tbody>
        </table>

        <p><strong>Prepared by:</strong> ___________________________ (HR Officer)</p>
        <p><strong>Noted by:</strong> ___________________________ (General Manager)</p>
        <p><strong>Received by:</strong> ___________________________ <br> Signature: ___________________________</p>
    </div>

    <script>
        function formatDate() {
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return today.toLocaleDateString(undefined, options);
        }

        function printTable() {
            // Update the Date Received field
            document.getElementById("date-received").textContent = formatDate();

            var printContents = document.getElementById("printableReceipt").innerHTML;
            var printWindow = window.open('', '', 'width=800,height=600');

            printWindow.document.write('<html><head><title>Original Copy</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
            printWindow.document.write('th, td { border: 1px solid black; padding: 5px; text-align: left; }');
            printWindow.document.write('</style></head><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.print();
        }

    </script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let deleteButton = document.getElementById("Delete");
    let tableBody = document.querySelector("#accountabilityTable tbody"); // ✅ Correct table ID

    function updateDeleteButtonState() {
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
    }

    // Run on page load to check initial state
    updateDeleteButtonState();

    // ✅ Re-check when data changes (for dynamic updates)
    const observer = new MutationObserver(updateDeleteButtonState);
    observer.observe(tableBody, { childList: true });

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
                let deleteForm = document.getElementById("deleteform"); // ✅ Get form again
                if (!deleteForm) {
                    console.error("❌ Delete form not found!");
                    Swal.fire("Error", "Delete form not found!", "error");
                    return;
                }

                console.log("✅ Form submitted!");
                deleteForm.submit();
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    let importButton = document.querySelector(".import-btn");
    let fileInput = document.querySelector("#file-upload");
    let fileNameDisplay = document.querySelector("#file-name");

    // ✅ Function to update file name display
    window.updateFileName = function () {
        if (fileInput.files.length > 0) {
            let fileName = fileInput.files[0].name;
            fileNameDisplay.textContent = fileName;
        } else {
            fileNameDisplay.textContent = "No file chosen";
        }
    };

    if (importButton) {
        importButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent form from submitting automatically

            // ✅ Check if a file is selected
            if (!fileInput.files.length) {
                Swal.fire({
                    title: "No File Selected!",
                    text: "Please choose an Excel (.xls, .xlsx) or CSV file before importing.",
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
                title: "Import Excel?",
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
                    importButton.closest("form").submit();
                }
            });
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    let exportButton = document.querySelector("#exportExcel"); // ✅ Updated to match your export button

    if (exportButton) {
        exportButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default export

            // ✅ Count table rows dynamically for #cdoTable
            let tableRows = document.querySelectorAll("#accountabilityTable tbody tr");
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
document.addEventListener("DOMContentLoaded", function () {
    let deleteButtons = document.querySelectorAll(".deleteButton");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Stop default form submission

            Swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you cannot recover this record!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, Delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest("form").submit(); // Submit the form if confirmed
                }
            });
        });
    });
});
</script>


    @endsection
