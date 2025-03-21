@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container mt-4">
    <h2 class="text-primary">Gingoog Records</h2>

    {{-- File Upload and Search --}}
    <form action="{{ route('gingoogs.import') }}" id="ImportForm" method="POST" enctype="multipart/form-data"
    class="import-container p-2 border rounded d-inline-flex align-items-center gap-2" style="max-width: 500px;">
    @csrf
    <div class="d-flex align-items-center">
        <label for="file-upload" class="btn btn-primary mb-0">
            📂 Choose File
        </label>
        <input type="file" name="file" id="file-upload" class="d-none" required onchange="updateFileName()">
        <span id="file-name" class="ms-2 text-dark fw-bold">No file chosen</span>

        <!-- ✅ Add id="Import" to the button -->
        <button type="submit" class="btn btn-success" id="Import">
            📥 Import Excel
        </button>
    </div>
</form>
</div>
    {{-- Search Bar --}}
    <form method="GET" action="{{ route('gingoogs.records') }}">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    {{-- Print and Export Buttons --}}
    <div class="d-flex align-items-center" id="printContainer">
<button onclick="printTable()" class="btn btn-primary mb-3">🖨️ Print Table</button>
<a href="{{ route('gingoogs.export') }}" class="btn btn-primary mb-3" id="ExportButton">📤 Export to Excel</a>
</div>
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
    @media print {
    .print-logo {
        width: 100px !important;
        height: 100px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        display: block !important;
    }
}

</style>
{{-- Hidden Printable Receipt --}}
<div id="printableReceipt" style="display: none;">
<img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('2.jpg'))) }}" class="print-logo"
style="width: 100px !important; height: 100px !important; border-radius: 50% !important; object-fit: cover !important; display: block !important;">
    <h2 style="text-align: center;">Black Line Republic</h2>
    <h3 style="text-align: center;">ACKNOWLEDGEMENT RECEIPT FOR GINGOOG EQUIPMENT</h3>

    <p><strong>Name:</strong> {{ $gingoogRecords->first()->name ?? '___________________________' }}</p>
    <p><strong>Position:</strong> {{ $gingoogRecords->first()->position ?? '___________________________' }}</p>
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
            @foreach ($gingoogRecords as $gingoog)
                <tr>
                    <td style="border: 1px solid black; padding: 5px;">{{ $gingoog->date }}</td>
                    <td style="border: 1px solid black; padding: 5px;">{{ $gingoog->quantity }}</td>
                    <td style="border: 1px solid black; padding: 5px;">{{ $gingoog->description }}</td>
                    <td style="border: 1px solid black; padding: 5px;">
    {{ !in_array($gingoog->ser_no, ['Unknown', 'N/A']) ? $gingoog->ser_no : '' }}
</td>                                 <td style="border: 1px solid black; padding: 5px;">
    {{ !in_array($gingoog->status, ['Unknown', 'N/A']) ? $gingoog->status : '' }}
</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Prepared by:</strong> ___________________________ (HR Officer)</p>
    <p><strong>Noted by:</strong> ___________________________ (General Manager)</p>
    <p><strong>Received by:</strong> ___________________________ <br> Signature: ___________________________</p>
</div>

    {{-- Delete All Button --}}
    <div class="delete-container">
    <form id="deleteForm" action="{{ url('/gingoogs/delete-all') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" id="Delete" class="btn btn-danger"> <i class="fa-solid fa-trash" style="margin-right: 5px;"></i> Delete All</button>
    </form>
</div>


    {{-- Gingoog Records Table --}}
    <div class="card shadow-sm" id="gingoogTable">
        <div class="card-body">
            <h4 class="mb-3 text-primary">Gingoog Records</h4>

            <div class="table-responsive">
                <table id="gingoogTable" class="table table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Ser_No</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gingoogRecords as $record)
                            @php
                                // Check for duplicate serial number
                                $isDuplicate = \App\Models\Gingoog::where('ser_no', $record->ser_no)->count() > 1;
                            @endphp
                            <tr class="align-middle text-center">
                                <td>{{ $record->position }}</td>
                                <td>{{ $record->name }}</td>
                                <td>{{ $record->date ?? 'N/A' }}</td>
                                <td>{{ $record->quantity }}</td>
                                <td>{{ $record->description }}</td>
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
    <a href="{{ route('gingoogs.edit', $record->id) }}" class="btn btn-sm btn-warning EditTechnician me-1" data-id="{{ $gingoog->id }}">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('gingoogs.destroy', $record->id) }}" method="POST" class="deleteForm ms-1">
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
            {{ $gingoogRecords->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
<style>
 #Delete {
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
    justify-content: left; /* Align to the right */
    margin-bottom: 10px; /* Align to the right */
}

</style>

</script>
{{-- JavaScript for print --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ✅ Function to format the date
    function formatDate() {
        const today = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return today.toLocaleDateString(undefined, options);
    }

    // ✅ Function to handle printing
    window.printTable = function() {
    console.log("printTable() function is being called!"); // Debugging

    document.getElementById("date-received").textContent = formatDate();
    const printContents = document.getElementById("printableReceipt").innerHTML;
    const printWindow = window.open('', '', 'width=800,height=600');

    printWindow.document.write('<html><head><title>Gingoog Records</title>');
    printWindow.document.write('<style>body { font-family: Arial, sans-serif; padding: 20px; }</style></head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');

    printWindow.document.close();
    printWindow.print();
};

    // ✅ Delete Single Record with SweetAlert
    document.querySelectorAll(".deleteform").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
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
                    this.closest("form").submit();
                }
            });
        });
    });

    let deleteButton = document.getElementById("Delete");
    let deleteForm = document.getElementById("deleteForm");

    function updateDeleteButtonState() {
        let tableRows = document.querySelectorAll("#gingoogTable tbody tr");

        if (tableRows.length === 0) {
            deleteButton.disabled = true;  // Disable if no records
        } else {
            deleteButton.disabled = false; // Enable if records exist
        }
    }

    // Run check on page load and after AJAX changes (if applicable)
    updateDeleteButtonState();

    deleteButton.addEventListener("click", function (event) {
        event.preventDefault();

        let tableRows = document.querySelectorAll("#gingoogTable tbody tr");
        if (tableRows.length === 0) {
            Swal.fire({
                title: "No Records to Delete!",
                text: "There are no records available to delete.",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        Swal.fire({
            title: "Are you sure?",
            text: "All records will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete all!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(deleteForm.action, {
                    method: 'POST',
                    body: new FormData(deleteForm),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(response => {
                    if (response.ok) {
                        Swal.fire("Deleted!", "All records have been deleted.", "success")
                            .then(() => location.reload()); // ✅ Refresh page after deletion
                    } else {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                }).catch(error => {
                    console.error("Error:", error);
                    Swal.fire("Error!", "Something went wrong.", "error");
                });
            }
        });
    });
});
   document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript loaded successfully!"); // ✅ Debugging log

    let importButton = document.querySelector("#Import");
    let fileInput = document.querySelector("#file-upload");
    let fileNameDisplay = document.querySelector("#file-name");
    let importForm = document.querySelector("#ImportForm");

    // ✅ Function to update file name display
    window.updateFileName = function () {
        if (fileInput.files.length > 0) {
            let fileName = fileInput.files[0].name;
            fileNameDisplay.textContent = fileName;
        } else {
            fileNameDisplay.textContent = "No file chosen";
        }
    };

    if (importButton && fileInput) {
        importButton.addEventListener("click", function (event) {
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
        console.error("Import button or file input not found!"); // ✅ Debugging log
    }
});
document.addEventListener("DOMContentLoaded", function () {
    let exportButton = document.querySelector("#ExportButton");

    if (exportButton) {
        exportButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default export

            // ✅ Count table rows dynamically
            let tableRows = document.querySelectorAll("#gingoogTable tbody tr");
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
</script>
@endsection
