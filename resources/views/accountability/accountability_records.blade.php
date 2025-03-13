    @extends('layouts.app')

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
        font-weight: bold;
    }
    #Delete{
        margin-top: 5px; /* Space between search and delete button */
        margin-left: 960px;
            text-align: left;
        background-color:  #007bff;
        border: none;
        font-weight: bold;
        border-radius: 5px;
    }
    #Delete:hover{
        background-color:rgba(0, 123, 255, 0.88);
        transition: background-color 0.2s ease;
        color: white;
        cursor: pointer;
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
        <button type="button" id="Delete" class="btn btn-danger">Delete All</button>
    </form>
</div>
            <!-- Visible Table -->
            <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-3 text-primary">BRO Records</h4>

            <div class="table-responsive">
                <table id="accountabilityTable" class="table table-striped table-hover">
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
        @foreach ($records as $record)
            @php
                // Count how many times this serial number appears in the database
                $isDuplicate = \App\Models\AccountabilityRecord::where('ser_no', $record->ser_no)->count() > 1;
            @endphp

            <tr class="align-middle text-center">
                <td>{{ $record->id_number }}</td>
                <td>{{ $record->name }}</td>
                <td>{{ $record->date ?? 'N/A' }}</td>
                <td>{{ $record->quantity }}</td>
                <td>{{ $record->description }}</td>

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
                    <div class="btn-group">
                        <a href="{{ route('accountability.edit', $record->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('accountability.destroy', $record->id) }}" method="POST" class="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger deleteButton">
                            <i class="fas fa-trash-alt"></i> Delete
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
        <h2 style="text-align: center;">Black Line Republic</h2>
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
                        <td style="border: 1px solid black; padding: 5px;">{{ $record->ser_no }}</td>
                        <td style="border: 1px solid black; padding: 5px;">
        {{ $record->status !== 'Unknown' ? $record->status : '' }}
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
document.addEventListener("DOMContentLoaded", function () {
    let deleteButton = document.getElementById("Delete");

    if (deleteButton) {
        deleteButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent auto-submission

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
                    // ✅ Submit the form
                    document.getElementById("deleteform").submit();

                    // ✅ Show success alert after deletion
                    Swal.fire({
                        title: "Deleted!",
                        text: "All records have been deleted successfully.",
                        icon: "success",
                        timer: 2000, // Auto-close after 2 seconds
                        showConfirmButton: false
                    });
                }
            });
        });
    }
});
document.addEventListener("DOMContentLoaded", function () {
    let importButton = document.querySelector(".import-btn");

    if (importButton) {
        importButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent auto-submit

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
    let exportButton = document.querySelector("#exportExcel");

    if (exportButton) {
        exportButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent immediate navigation

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

                    // ✅ Redirect after the success alert
                    setTimeout(() => {
                        window.location.href = exportButton.href; // Proceed with export
                    }, 1500);
                }
            });
        });
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
