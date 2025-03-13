@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Technician Records</h2>

    {{-- File Upload and Search --}}
    <form action="{{ route('technician.import') }}" method="POST" enctype="multipart/form-data" class="import-container">
    @csrf
    <input type="file" name="file" id="file-upload" class="custom-file-input" required onchange="updateFileName()">
    <label for="file-upload" class="custom-file-label">
        📁 <strong>Choose File</strong>
    </label>
    <span id="file-name">No file chosen</span>
    <button type="submit" class="import-btn">
    📥 <strong>Import Excel</strong>
    </button>
</form>

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('technician.records') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        <style>
    .record-header {
        display: flex;
        align-items: center;
        gap: 10px; /* Adjust spacing */
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
    background-color:rgba(24, 89, 159, 0.88);
    transition: background-color 0.2s ease;
    color: white;
    cursor: pointer;
}
</style>
    </div>

    {{-- Print Button --}}
    <button onclick="printTable()" class="btn btn-primary mb-3">🖨️ Print Table</button>
    <a href="{{ route('export.technicians') }}" id="exportexcel" class="btn btn-primary mb-3">📤 Export to Excel</a>
    <div class="mt-2 text-center">
    <form id="deleteForm" action="{{ route('technician.deleteAll') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" id="Delete" class="btn btn-danger">Delete All</button>
    </form>
</div>
</div>
    {{-- Technician Records Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-3 text-primary">Technician Records</h4>

            <div class="table-responsive">
                <table id="technicianTable" class="table table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Position</th>
                            <th>Tech/Name</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Ser_No</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    </thead>
                    <tbody>
                        @foreach ($technicians as $technician)
                            @php
                                // Check for duplicate serial number
                                $isDuplicate = \App\Models\Technician::where('ser_no', $technician->ser_no)->count() > 1;
                            @endphp
                            <tr class="align-middle text-center">
                                <td>{{ $technician->position }}</td>
                                <td>{{ $technician->name }}</td>
                                <td>{{ $technician->date ?? 'N/A' }}</td>
                                <td>{{ $technician->quantity }}</td>
                                <td>{{ $technician->description }}</td>
                                <td style="color: {{ $isDuplicate ? 'red' : 'black' }};">
                                    {{ $technician->ser_no ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge 
                                        {{ $technician->status == 'NEW' ? 'bg-success' : ($technician->status == 'Unknown' ? 'bg-secondary' : 'bg-warning') }}">
                                        {{ $technician->status }}
                                    </span>
                                </td>
                                <td>
                                <div class="btn-group">
                                    <a href="#" class="btn btn-sm btn-warning EditTechnician" data-id="{{ $technician->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>              
                                        <form action="{{ route('technician.destroy', $technician->id) }}" id="deleteForm1" method="POST">
                                      @csrf
                                            @method('DELETE')
                                            <button type="button" id="Delete1" class="btn btn-sm btn-danger">
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

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $technicians->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    {{-- Hidden Printable Receipt --}}
    <div id="printableReceipt" style="display: none;">
        <h2 style="text-align: center;">Black Line Republic</h2>
        <h3 style="text-align: center;">ACKNOWLEDGEMENT RECEIPT FOR TECHNICIAN EQUIPEMENT</h3>

        <p><strong>Name:</strong> {{ $technicians->first()->name ?? '___________________________' }}</p>
        <p><strong>Position:</strong> {{ $technicians->first()->position ?? '___________________________' }}</p>
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
                @foreach ($technicians as $technician)
                    <tr>
                        <td style="border: 1px solid black; padding: 5px;">{{ $technician->date }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $technician->quantity }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $technician->description }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $technician->ser_no }}</td>
                        <td style="border: 1px solid black; padding: 5px;">
    {{ $technician->status !== 'Unknown' ? $technician->status : '' }}
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
    function updateFileName() {
        const fileInput = document.getElementById('file-upload');
        const fileNameSpan = document.getElementById('file-name');
        fileNameSpan.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : "No file chosen";
    }

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

        printWindow.document.write('<html><head><title>Technician Records</title>');
        printWindow.document.write('<style>body { font-family: Arial, sans-serif; padding: 20px; }</style></head><body>');
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
                    document.getElementById("deleteForm").submit();

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
    let exportButton = document.querySelector("#exportexcel");

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
    let deleteButton = document.getElementById("Delete1");

    if (deleteButton) {
        deleteButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default form submission

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
                    document.getElementById("deleteForm1").submit(); // Manually submit form if confirmed
                }
            });
        });
    } else {
        console.error("Delete button not found! Check your HTML ID.");
    }
});
document.addEventListener("DOMContentLoaded", function () {
    let editButtons = document.querySelectorAll(".EditTechnician"); // Select all Edit buttons

    editButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default click action

            let technicianId = this.getAttribute("data-id"); // Get technician ID from data attribute

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
                    window.location.href = `/technician/${technicianId}/edit`; // Redirect if confirmed
                }
            });
        });
    });
});
</script>
@endsection
