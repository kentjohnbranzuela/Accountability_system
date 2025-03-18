@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="text-primary">CDO Records</h2>

    {{-- File Upload and Search --}}
  <form action="{{ route('cdos.import') }}" 
    id="ImportForm" 
    method="POST" 
    enctype="multipart/form-data"
    class="import-container p-2 border rounded d-inline-flex align-items-center gap-2" 
    style="max-width: 500px;">
    @csrf
    <div class="d-flex align-items-center">
        <label for="file-upload" class="btn btn-primary mb-0">
            📂 Choose File
        </label>
        <input type="file" name="file" id="file-upload" class="d-none" required onchange="updateFileName()">
        <span id="file-name" class="ms-2 text-dark fw-bold">No file chosen</span>
        <button type="submit" class="btn btn-success" id="Import">
            📥 Import Excel
        </button>
    </div>
</form>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('cdos.records') }}">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    {{-- Print and Export Buttons --}}
    <button onclick="printTable()" class="btn btn-primary mb-3">🖨️ Print Table</button>
    <a href="{{ route('cdos.export') }}" class="btn btn-primary mb-3" id="exportExcel">📤 Export to Excel</a>

    {{-- Delete All Button --}}
    <div class="mt-2 text-center">
    <form id="deleteForm" action="{{ route('cdos.deleteAll') }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="button" id="Delete" class="btn btn-danger"><i class="fa-solid fa-trash" style="margin-right: 5px;"></i> Delete All</button>
</form>
    </div>

    {{-- CDO Records Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-3 text-primary">CDO Records</h4>

            <div class="table-responsive">
                <table id="cdoTable" class="table table-striped table-hover">
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
                        @foreach ($cdos as $cdo)
                            <tr class="align-middle text-center">
                                <td>{{ $cdo->position }}</td>
                                <td>{{ $cdo->name }}</td>
                                <td>{{ $cdo->date ?? 'N/A' }}</td>
                                <td>{{ $cdo->quantity }}</td>
                                <td>{{ $cdo->description }}</td>
                                <td>{{ $cdo->ser_no ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge
                                        {{ $cdo->status == 'NEW' ? 'bg-success' : ($cdo->status == 'Unknown' ? 'bg-secondary' : 'bg-warning') }}">
                                        {{ $cdo->status }}
                                    </span>
                                </td>
                                <td>
                                <div class="btn-group">
    <a href="{{ route('cdos.edit', $cdo->id) }}" class="btn btn-sm btn-warning">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('cdos.destroy', $cdo->id) }}" method="POST" class="deleteForm ms-2" >
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
            {{ $cdos->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
<!-- Hidden Printable Receipt -->
<div id="printableReceipt" style="display: none;">
    <!-- Logo -->
    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('2.jpg'))) }}" class="print-logo"
        style="width: 100px !important; height: 100px !important; border-radius: 50% !important; object-fit: cover !important; display: block !important;">

    <!-- Title -->
    <h2 style="text-align: center;">Black Line Republic</h2>
    <h3 style="text-align: center;">ACKNOWLEDGEMENT RECEIPT FOR CDO EQUIPMENT</h3>

    <!-- Dynamic Data -->
    <p><strong>Name:</strong> {{ $cdos->first()->name ?? '___________________________' }}</p>
    <p><strong>Position:</strong> {{ $cdos->first()->position ?? '___________________________' }}</p>
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
            @foreach ($cdos as $cdo)
                <tr>
                    <td style="border: 1px solid black; padding: 5px;">{{ $cdo->date }}</td>
                    <td style="border: 1px solid black; padding: 5px;">{{ $cdo->quantity }}</td>
                    <td style="border: 1px solid black; padding: 5px;">{{ $cdo->description }}</td>
                    <td style="border: 1px solid black; padding: 5px;">
                        {{ !in_array($cdo->ser_no, ['Unknown', 'N/A']) ? $cdo->ser_no : '' }}
                    </td>
                    <td style="border: 1px solid black; padding: 5px;">
                        {{ !in_array($cdo->status, ['Unknown', 'N/A']) ? $cdo->status : '' }}
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
    document.addEventListener("DOMContentLoaded", function () {
    let exportButton = document.querySelector("#exportExcel"); // ✅ Updated to match your export button
    
    if (exportButton) {
        exportButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default export

            // ✅ Count table rows dynamically for #cdoTable
            let tableRows = document.querySelectorAll("#cdoTable tbody tr");
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
     function formatDate() {
        const today = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return today.toLocaleDateString(undefined, options);
    }

      function printTable() {
        // Update the Date Received field
        document.getElementById("date-received").textContent = formatDate();

        const printContents = document.getElementById("printableReceipt").innerHTML;
        const printWindow = window.open('', '', 'width=800,height=600');

        printWindow.document.write('<html><head><title>Gingoog Records</title>');
        printWindow.document.write('<style>body { font-family: Arial, sans-serif; padding: 20px; }</style></head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.print();
    }
     document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".deleteButton").forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent form submission
                let form = this.closest(".deleteForm");

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
                        form.submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    });
    //Delete All
    document.addEventListener("DOMContentLoaded", function () {
    let importButton = document.querySelector("#Import");
    let fileInput = document.querySelector("#file-upload");
    let fileNameDisplay = document.querySelector("#file-name");

    // ✅ Update file name when user selects a file
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
            event.preventDefault(); // Prevent form from submitting immediately

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

            // ✅ Validate file extension (allowing .xls, .xlsx, .csv)
            let fileName = fileInput.files[0].name;
            let allowedExtensions = /(\.xls|\.xlsx|\.csv)$/i; // ✅ Now allows CSV files too

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

            // ✅ Show confirmation alert before importing
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
                    document.querySelector("#ImportForm").submit();
                }
            });
        });
    }
});
document.addEventListener("DOMContentLoaded", function () {
    let deleteButton = document.getElementById("Delete");

    deleteButton.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default form submission

        // ✅ Check if table has data before deleting
        let tableRows = document.querySelectorAll("#cdoTable tbody tr"); // Ginawang tama ang ID

        if (tableRows.length === 0) {
            Swal.fire({
                title: "No Records to Delete!",
                text: "There are no records available to delete.",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        // ✅ Show confirmation popup before deleting
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
                fetch(document.getElementById('deleteForm').action, {
                    method: 'POST',
                    body: new FormData(document.getElementById('deleteForm')),
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
</script>
<style>
    #Delete{
        margin-top: 5px; /* Space between search and delete button */
        margin-left: 890px;
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
@endsection
