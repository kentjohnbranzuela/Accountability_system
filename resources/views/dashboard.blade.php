@extends('layouts.app')

@section('content')
<div class="container mt-4">

  <div class="custom-grid">
    @foreach ($dataCounts as $category => $count)
        @php
            // Define routes for each category
            $routes = [
                'BRO' => route('accountability.accountability_records'),
                'Gingoog' => route('gingoogs.records'),
                'Technician' => route('technician.records'),
                'BC-CDO LIST' => route('cdos.records'),
                'TURN-OVER LIST' => route('turnover.records'),
                'AWOL LIST' => route('awol.records'),
                'RESIGN-LIST' => route('resign.records'),
            ];
            $route = $routes[$category] ?? '#'; // Default to '#' if no route exists
        @endphp

        <a href="{{ $route }}" class="grid-item">
            <div class="category">{{ $category }}</div>
            <div class="count">{{ $count }}</div>
        </a>
    @endforeach
</div>

    <!-- Accountability Dashboard Section -->
    <div class="card shadow-lg p-4 border-0 rounded-4 mb-4">
        <h2 class="mb-4 text-center text-primary fw-bold">📊 Assest Dashboard</h2>

        <!-- Yearly Filter (Hidden in Print) -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-3 text-center filter-container">
            <label for="year" class="fw-bold me-2">Filter by Year:</label>
           <select name="year">
    <option value="">All Years</option> <!-- This option will show all years by default -->
    @foreach($years as $year)
        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
    @endforeach
</select>
            <form method="GET" action="{{ route('dashboard') }}" class="mb-3 text-center">
    <label for="source" class="fw-bold me-2">Filter by Source:</label>
    <select name="source" id="source" class="form-select w-auto d-inline shadow-sm" onchange="this.form.submit()">
        <option value="">All Sources</option>
        @foreach ($sources as $source)
            <option value="{{ $source }}" {{ $selectedSource == $source ? 'selected' : '' }}>
                {{ $source }}
            </option>
        @endforeach
    </select>
</form>
<style>
   .custom-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 12px;
    padding: 10px;
}

/* Animation on Load */
.grid-item {
    text-decoration: none;
    background-color: white;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 16px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 120px;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.5s ease forwards;
}

/* Delayed animation for each item */
.grid-item:nth-child(1) { animation-delay: 0.1s; }
.grid-item:nth-child(2) { animation-delay: 0.2s; }
.grid-item:nth-child(3) { animation-delay: 0.3s; }
.grid-item:nth-child(4) { animation-delay: 0.4s; }
.grid-item:nth-child(5) { animation-delay: 0.5s; }
.grid-item:nth-child(6) { animation-delay: 0.6s; }
.grid-item:nth-child(7) { animation-delay: 0.7s; }
.grid-item:nth-child(1) { background-color: #fef3c7; } /* Light Yellow */
.grid-item:nth-child(2) { background-color: #d1fae5; } /* Light Green */
.grid-item:nth-child(3) { background-color: #cecece; } /* Light Blue */
.grid-item:nth-child(4) { background-color: #fbcfe8; } /* Light Pink */
.grid-item:nth-child(5) { background-color: #d9f0f4; } /* Light Orange */
.grid-item:nth-child(6) { background-color: #c7d2fe; } /* Light Purple */
.grid-item:nth-child(7) { background-color: #f2e8e8; } /* Light Red */

/* Hover Effect - Scale Up */
.grid-item:hover {
    transform: scale(1.1); /* Mas malaki */
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25); /* Mas intense ang shadow */
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

/* Category Styling */
.category {
    text-decoration: none;
    color: #000000;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
    min-height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    word-wrap: break-word;
    padding: 5px;
}

/* Count Styling */
.count {
    font-size: 24px;
    font-weight: bold;
    color: #2563eb;
}

/* Keyframe Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn-gradient {
        background-color: #0bac00bd;
        color: black;
        border: none;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        background: linear-gradient(135deg, #183b2dbb, #183822);
        color: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .btn-gradient i {
        margin-right: 2px;
    }
    </style>
        </form>
    </div>
          <!-- Search and Print Controls Section -->
<div class="card shadow-lg p-3 border-0 rounded-4 mb-4 search-print-container">
    <div class="text-center">
        <!-- Search Bar -->
        <div class="input-group mb-3 w-50 mx-auto shadow-sm">
            <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Search description...">
            <button class="btn btn-primary" onclick="searchData()"><i class="fas fa-search"></i> Search</button>
        </div>

        <!-- Print Button -->
        <button class="btn btn-gradient shadow-lg print-btn px-4 py-2 rounded-pill" onclick="prepareForPrint()">
    <i class="fas fa-print" id="print"></i>  <strong>Print Report</strong>
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
                <td class="print-only">{{ $item->year ?? 'N/A' }}</td> <!-- FIX: Show year per row -->
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
    .custom-grid {
        display: none !important;
    }
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
