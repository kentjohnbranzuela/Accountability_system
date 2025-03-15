<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountabilityRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountabilityImport;
use App\Exports\BROExport;

class AccountabilityRecordsController extends Controller
{
    public function index()
    {
        return view('accountability.index'); // Ensure accountability/index.blade.php exists
    }

    public function accountability_records(Request $request)
    {
        // Fetch records with pagination and search functionality
        $query = AccountabilityRecord::query();

        // General search across multiple fields
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_number', 'LIKE', "%$search%")
                  ->orWhere('name', 'LIKE', "%$search%")
                  ->orWhere('description', 'LIKE', "%$search%")
                  ->orWhere('ser_no', 'LIKE', "%$search%")
                  ->orWhere('status', 'LIKE', "%$search%");
            });
        }

        // Specific filtering by name
        if ($request->has('filter_name') && $request->filter_name != '') {
            $query->where('name', $request->filter_name);
        }

        // Paginate results (adjust the number if needed)
        $records = $query->paginate(19);

        return view('accountability.accountability_records', compact('records'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'id_number' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
            'quantity' => 'nullable|integer', // Allow quantity to be nullable but must be an integer if provided
        ]);
    
        // Assign "N/A" if status is missing
        $status = $request->filled('status') ? $request->status : 'N/A';
    
        // Assign 0 if quantity is missing
        $quantity = $request->filled('quantity') ? $request->quantity : 0;
    
        // Insert into database
        AccountabilityRecord::create([
            'id_number' => $request->id_number,
            'name' => $request->name,
            'date' => $request->date,
            'quantity' => $quantity,
            'description' => $request->description,
            'ser_no' => $request->ser_no,
            'status' => $status,
        ]);
    
        return redirect()->back()->with('success', 'Record added successfully!');
    }

    public function edit($id)
    {
        $record = AccountabilityRecord::findOrFail($id);
        return view('accountability.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $record = AccountabilityRecord::findOrFail($id);

        $request->validate([
            'id_number' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',

        ]);

        $record->update($request->all());

        return redirect()->route('accountability.accountability_records')->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        $record = AccountabilityRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('accountability.accountability_records')->with('success', 'Record deleted successfully!');
    }
    public function importExcel(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Import the file
        Excel::import(new AccountabilityImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Imported Successfully!');
    }
    public function exportExcel()
    {
        return Excel::download(new BROExport, 'BRO.xls');
    }
    public function deleteAll()
{
    AccountabilityRecord::truncate(); // Deletes all records but keeps the table structure
    return redirect()->back()->with('success', 'All records have been deleted successfully.');
}
}
