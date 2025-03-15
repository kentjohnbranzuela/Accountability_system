@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Accountability Dashboard Section -->
    <div class="card shadow-lg p-4 border-0 rounded-4 mb-4">
        <h2 class="mb-4 text-center text-primary fw-bold">📊 Assest Dashboard</h2>

        <!-- Yearly Filter (Hidden in Print) -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-3 text-center filter-container">
            <label for="year" class="fw-bold me-2">Filter by Year:</label>
            <select name="year" id="year" class="form-select w-auto d-inline shadow-sm"
                onchange="this.form.submit()">
                @foreach ($years as $year)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Search and Print Controls Section -->
    <div class="card shadow-lg p-3 border-0 rounded-4 mb-4 search-print-container">
        <div class="text-center">
            <!-- Search Bar -->
            <div class="input-group mb-3 w-50 mx-auto shadow-sm">
                <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search description...">
            </div>

            <!-- Print Button -->
            <button class="btn btn-success shadow-sm print-btn" onclick="prepareForPrint()">
                <i class="fas fa-print"></i> Print Report
            </button>
        </div>
    </div>

    <!-- Print Header (Visible Only in Print) -->
    <div class="print-header">
    <img src="{{ asset('2.jpg') }}" class="print-logo" alt="Logo">
    <h2 class="text-center">Black Line Republic</h2>
    <h4 class="text-center fw-bold">ACKNOWLEDGEMENT RECEIPT FOR EQUIPMENT</h4>
    <p><strong>Date Received:</strong> <span id="date-received">_____________________</span></p>
</div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        function formatDate() {
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return today.toLocaleDateString(undefined, options);
        }

        function updatePrintDate() {
            document.getElementById("date-received").textContent = formatDate();
        }

        // Update date before printing
        window.addEventListener("beforeprint", updatePrintDate);
    });
</script>

    <!-- Data Table Section -->
    <div class="container-fluid mt-4 table-container">
    <table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Description</th>
        <th>Count</th>
        <th>Source</th> <!-- NEW COLUMN -->
        <th class="print-only">Year</th>
    </tr>
</thead>
<tbody id="tableBody">
    @if ($mergedData->isEmpty())
        <tr>
            <td colspan="5" class="text-center text-danger">{{ $message ?? 'No data available' }}</td>
        </tr>
    @else
        @foreach ($mergedData as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->count }}</td>
                <td>{{ $item->source }}</td> <!-- DISPLAY DATA SOURCE -->
                <td class="print-only">{{ $selectedYear }}</td>
            </tr>
        @endforeach
    @endif
</tbody>


    <!-- Print-Friendly Styles -->
    <style>
        .print-header {
    display: none !important;
}
@media print {
    .print-header {
        display: flex !important;
        align-items: center;
        justify-content: flex-start; /* Align to the left */
    }
    .print-logo-container {
        width: 100px; /* Adjust as needed */
        margin-right: 20px; /* Add spacing from text */
    }
    .print-logo {
        max-width: 100px; /* Adjust size */
        height: 100px; /* Ensure it's a perfect circle */
        border-radius: 50%; /* Make it round */
        object-fit: cover; /* Maintain aspect ratio */
        display: block;
    }
    .print-header-text {
        flex-grow: 1;
        text-align: left;
    }
    .print-header {
        display: block !important;
    }
    footer {
        display: none !important;
    }
    .header,  /* Adjust this to match your actual header class */
    .footer,  /* Adjust this to match your actual footer class */
    .navbar,  /* If your navigation bar is also part of the header */
    .site-info { /* If there's any extra section causing issues */
        display: none !important;
    }
    .mb-4{
        display:none !important;
    }
    /* Hide sidebar if applicable */
    .sidebar { 
        display: none !important; 
        width: 0 !important;
    }

    /* Make content full-width */
    .content {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto !important;
        padding: 0 !important;
    }

    /* Adjust body layout */
    body, html {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
    }

    /* Define page margins */
    @page {
        size: auto;
        margin: 10mm;
    }
}

</style>

    <!-- Search Functionality -->
    <script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#tableBody tr");

        rows.forEach(row => {
            let desc = row.cells[1].textContent.toLowerCase();
            row.style.display = desc.includes(value) ? "" : "none";
        });
    });

    function prepareForPrint() {
        window.print();
    }
    </script>

</div>
@endsection
