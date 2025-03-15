<?php

namespace App\Http\Controllers;

use App\Exports\GingoogExport;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GingoogImport;
use Illuminate\Http\Request;
use App\Models\Gingoog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GingoogController extends Controller
{
    
    public function records(Request $request)
{
    $query = Gingoog::query();

    // Search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('position', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%")
                ->orWhere('quantity', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('ser_no', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        });
    }

    // Paginate the results
    $gingoogRecords = $query->paginate(19);

    return view('gingoogs.records', compact('gingoogRecords'));
}


    // Show the form for creating a new record
    public function create()
    {
        return view('gingoogs.create');
    }

    // Store a new record
    public function store(Request $request)
    {
        $request->validate([
            'position' => 'nullable|string', 
            'name' => 'required|string',
            'date' => 'required|date',
            'quantity' => 'nullable|integer|min:0', // Ensure quantity is an integer
            'description' => 'nullable|string',
            'ser_no' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
    
        // Store data with default values
        Gingoog::create([
            'position' => $request->position,
            'name' => $request->name,
            'date' => $request->date,
            'quantity' => $request->quantity ?? 0, // Default to 0 if NULL
            'description' => $request->description ?? 'N/A',
            'ser_no' => $request->ser_no ?? 'N/A',
            'status' => $request->status ?? 'Unknown',
        ]);
    
        return redirect()->route('gingoogs.records')->with('success', 'Record added successfully!');
    }
    // Show a single record
    public function show($id)
    {
        $gingoog = Gingoog::findOrFail($id);
        return view('gingoogs.show', compact('gingoog'));
    }

    // Show the form for editing a record
    public function edit($id)
    {
        $gingoog = Gingoog::findOrFail($id);
        return view('gingoogs.edit', compact('gingoog'));
    }

    // Update a record
    public function update(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string',
        'date' => 'required|date',
    ]);

    // Find the record first
    $gingoog = Gingoog::findOrFail($id);

    // Update the record with the request data
    $gingoog->update([
        'position' => $request->position ?? $gingoog->position,        'name' => $request->name,
        'date' => $request->date,
        'quantity' => $request->quantity ?? 'N/A',
        'description' => $request->description ?? 'N/A',
        'ser_no' => $request->ser_no ?? 'N/A',
        'status' => $request->status ?? 'N/A', // ✅ If NULL, it will be "N/A"
    ]);

    // Redirect back with a success message
    return redirect()->route('gingoogs.records')->with('success', 'Record updated successfully!');
}
    // Delete a record
    public function destroy($id)
    {
        $gingoog = Gingoog::findOrFail($id);
        $gingoog->delete();

        return redirect()->route('gingoogs.records')->with('success', 'Record deleted successfully!');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
    
        Excel::import(new GingoogImport, $request->file('file'));
    
        return back()->with('success', 'Records imported successfully!');
    }
    //Delete all
    public function deleteAll()
{
    Gingoog::truncate(); // This will delete all records
    return redirect()->back()->with('success', 'All records have been deleted successfully.');
}
public function showGingoogRecords()
{
    $gingoogRecords = Gingoog::paginate(10); // ✅ Use paginate()
    return view('gingoogs.records', compact('gingoogRecords'));
}
public function export()
{
    return Excel::download(new GingoogExport, 'Gingoog.csv');
}
}
