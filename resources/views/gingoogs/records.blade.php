@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Gingoog Records</h2>

    {{-- File Upload and Search --}}
    <form action="{{ route('gingoogs.import') }}"  id="ImportForm" method="POST" enctype="multipart/form-data"
    class="import-container p-2 border rounded d-inline-flex align-items-center gap-2" style="max-width: 500px;">
    @csrf
    <div class="d-flex align-items-center">
        <label for="file-upload" class="btn btn-primary mb-0">
            📂 Choose File
        </label>
        <input type="file" name="file" id="file-upload" class="d-none" required onchange="updateFileName()">
        <span id="file-name" class="ms-2 text-dark fw-bold">No file chosen</span>
    <button type="submit" class="btn btn-success">
        📥 Import Excel
    </button>
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
<button onclick="printTable()" class="btn btn-primary mb-3">🖨️ Print Table</button>
<a href="{{ route('gingoogs.export') }}" class="btn btn-primary mb-3">📤 Export to Excel</a>

<style>
    @media print {
    .print-logo {
        width: 100px !important;
        height: 100px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        display: block !important;
    }
}
.table-responsive {
    overflow-x: auto;
    max-width: 100%;
    white-space: nowrap;
}
td, th {
    word-wrap: break-word;
    white-space: normal;
}
.btn-group {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}
.d-flex.justify-content-end {
    flex-wrap: wrap;
    overflow-x: auto;
}
#edit {
    padding: 5px; /* Adjust padding for a better fit */
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap; /* Prevents text from wrapping */
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
    <div class="mt-2 text-center">
    <form id="deleteForm" action="{{ url('/gingoogs/delete-all') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" id="Delete" class="btn btn-danger">Delete All</button>
    </form>
</div>


    {{-- Gingoog Records Table --}}
   <div class="card shadow-sm">
    <div class="card-body">
        <h4 class="mb-3 text-primary">Gingoog Records</h4>
        <div class="table-responsive">
            <table id="gingoogTable" class="table table-striped table-hover w-100" style="table-layout: fixed;">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 10%;">Position</th>
                        <th style="width: 15%;">Name</th>
                        <th style="width: 10%;">Date</th>
                        <th style="width: 10%;">Quantity</th>
                        <th style="width: 20%;">Description</th>
                        <th style="width: 15%;">Serial No</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 20%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gingoogRecords as $record)
                        <tr class="align-middle text-center">
                            <td>{{ $record->position }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ $record->date ?? 'N/A' }}</td>
                            <td>{{ $record->quantity }}</td>
                            <td>{{ $record->description }}</td>
                            <td>{{ $record->ser_no ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $record->status == 'NEW' ? 'bg-success' : ($record->status == 'Unknown' ? 'bg-secondary' : 'bg-warning') }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('gingoogs.edit', $record->id) }}" class="btn btn-sm btn-warning"
                                        id="edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('gingoogs.destroy', $record->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
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
#Delete{
        margin-top: 5px; /* Space between search and delete button */
        margin-left: 755px;
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
{{-- JavaScript for print --}}
<script>
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
</script>
{{-- JavaScript for Alerts and Actions --}}
<script>
    //import button
    function updateFileName() {
        var input = document.getElementById('file-upload');
        var fileNameSpan = document.getElementById('file-name');
        if (input.files.length > 0) {
            fileNameSpan.textContent = input.files[0].name;
        } else {
            fileNameSpan.textContent = "No file chosen";
        }
    }
    //sweet alert
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".DeleteRecord").forEach(button => {
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

        document.getElementById("Delete").addEventListener("click", function (event) {
            event.preventDefault();
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
                    document.getElementById("deleteForm").submit();
                }
            });
        });
    });
    document.getElementById('ImportForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Para hindi agad mag-submit

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to import this Excel file?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, import it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Importing...',
                    text: 'Please wait while we process your file.',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Pag confirmed, tuloy ang submission
                event.target.submit();
            }
        });
    });
</script>
@endsection
