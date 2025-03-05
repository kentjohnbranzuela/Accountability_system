@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary">Accountability Records</h2>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('accountability.accountability_records') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <!-- Print Button -->
        <button onclick="printTable()" class="btn btn-primary">Print Table</button>

        <!-- Visible Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <table id="accountabilityTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
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
                            <tr>
                                <td>{{ $record->id_number }}</td>
                                <td>{{ $record->name }}</td>
                                <td>{{ $record->date }}</td>
                                <td>{{ $record->quantity }}</td>
                                <td>{{ $record->description }}</td>
                                <td>{{ $record->ser_no }}</td>
                                <td>{{ $record->status }}</td>
                                <td>
                                    <a href="{{ route('accountability.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('accountability.destroy', $record->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $records->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>


        <!-- Hidden Printable Receipt -->
<div id="printableReceipt" style="display: none;">
    <h2 style="text-align: center;">Black Line Republic</h2>
    <h3 style="text-align: center;">ACKNOWLEDGEMENT RECEIPT FOR EQUIPMENT</h3>

    <!-- Dynamically insert the first name & position (ID Number) from the records -->
    <p><strong>Name:</strong> {{ $records->first()->name ?? '___________________________' }}</p>
    <p><strong>Position:</strong> {{ $records->first()->id_number ?? '___________________________' }}</p>
    <p><strong>Date Received:</strong> _____________________</p>

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
                    <td style="border: 1px solid black; padding: 5px;">{{ $record->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Prepared by:</strong> ___________________________ (HR Officer)</p>
    <p><strong>Noted by:</strong> ___________________________ (General Manager)</p>
    <p><strong>Received by:</strong> ___________________________ <br> Signature: ___________________________</p>
</div>

    <script>
        function printTable() {
            var printContents = document.getElementById("printableReceipt").innerHTML;
            var originalContents = document.body.innerHTML;

            // Open a new printing window
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

@endsection
