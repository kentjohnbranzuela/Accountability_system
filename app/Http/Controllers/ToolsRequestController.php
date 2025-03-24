<?php

namespace App\Http\Controllers;

use App\Models\ToolsRequest;
use App\Exports\ToolsRequestExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Imports\ToolsRequestImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;

class ToolsRequestController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('technician'); // <-- Dapat tugma sa Kernel.php
    // }

    public function index()
    {
        return view('ToolsRequest.records'); // <-- Siguraduhin may view file
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Ensure all input fields are arrays
    $request->validate([
        'position.*' => 'nullable|string',
        'name.*' => 'required|string',
        'date.*' => 'required|date',
        'quantity.*' => 'nullable|integer',
        'description.*' => 'required|string',
        'ser_no.*' => 'nullable|string',
        'status.*' => 'nullable|string',
    ]);

    // Loop through the data and save each entry
    foreach ($request->name as $key => $name) {
        ToolsRequest::create([
            'position' => $request->position[$key] ?? null,
            'name' => $name,
            'date' => $request->date[$key],
            'quantity' => $request->quantity[$key] ?? 0,
            'description' => $request->description[$key],
            'ser_no' => $request->ser_no[$key] ?? 'N/A',
            'status' => $request->status[$key] ?? 'N/A',
        ]);
    }

    return redirect()->route('toolsrequest.records')->with('success', 'Tools Request records added.');
}

    /**
     * Delete all records.
     */
    public function deleteAll()
    {
        ToolsRequest::truncate();
        return redirect()->route('toolsrequest.records')->with('success', 'All Tools Request records deleted.');
    }

    /**
     * Display a listing of the resource with search functionality.
     */
    public function records(Request $request)
    {
        $query = ToolsRequest::query();

        // Check if there's a search input
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('position', 'LIKE', "%$search%")
                  ->orWhere('name', 'LIKE', "%$search%")
                  ->orWhere('description', 'LIKE', "%$search%")
                  ->orWhere('ser_no', 'LIKE', "%$search%")
                  ->orWhere('status', 'LIKE', "%$search%");
            });
        }

        // Fetch records with pagination
        $toolsrequests = $query->paginate(30); // Adjust pagination as needed

        return view('toolsrequest.records', compact('toolsrequests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAccount(Request $request, $id)
    {
        $toolsrequest = ToolsRequest::findOrFail($id);
        $toolsrequest->update($request->all());

        return redirect()->route('toolsrequest.records')->with('success', 'Tools Request record updated.');
    }

    /**
     * Export records to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new ToolsRequestExport, 'toolsrequests.xlsx');
    }

    /**
     * Import records from Excel.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new ToolsRequestImport, $request->file('file'));

        return back()->with('success', 'ToolsRequest records imported successfully!');
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $toolsrequest = new ToolsRequest;
        return view('toolsrequest.create', compact('toolsrequest'));
    }

    /**
     * Delete the specified record.
     */
    public function destroy($id)
    {
        $record = ToolsRequest::findOrFail($id);
        $record->delete();

        return redirect()->route('toolsrequest.records')->with('success', 'Record deleted successfully!');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit($id)
    {
        $record = ToolsRequest::findOrFail($id);
        return view('toolsrequest.edit', compact('record'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, $id)
    {
        $record = ToolsRequest::findOrFail($id);

        $request->validate([
            'position' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
        ]);

        $record->update($request->all());

        return redirect()->route('toolsrequest.records')->with('success', 'Record updated successfully!');
    }

    public function show($id)
{
    $technician = User::where('role', 'technician')->findOrFail($id);
    return view('toolsrequest.records', compact('technician'));
}
}
