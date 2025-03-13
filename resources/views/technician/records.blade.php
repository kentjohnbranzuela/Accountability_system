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
        ✅ <strong>Import Excel</strong>
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
</style>
    </div>

    {{-- Print Button --}}
    <button onclick="printTable()" class="btn btn-primary mb-3">🖨️ Print Table</button>

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
                                        <a href="{{ route('technician.edit', $technician->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('technician.destroy', $technician->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
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
@endsection
