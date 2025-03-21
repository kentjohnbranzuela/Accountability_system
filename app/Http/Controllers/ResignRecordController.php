<?php

namespace App\Http\Controllers;

use App\Models\ResignRecord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Exports\ResignExport;
use App\Imports\ResignImport;

class ResignRecordController extends Controller
{
    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'position' => 'nullable|string',
            'name' => 'required|string',
            'date' => 'required|date',
            'quantity' => 'nullable|integer',
            'description' => 'required|string',
            'ser_no' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        // Set default values if fields are empty
        $validatedData['quantity'] = $validatedData['quantity'] ?? 0;
        $validatedData['ser_no'] = $validatedData['ser_no'] ?? 'N/A';
        $validatedData['status'] = $validatedData['status'] ?? 'N/A';

        // Save to database
        ResignRecord::create($validatedData);

        return redirect()->route('resign.records')->with('success', 'Resign record added.');
    }

    /**
     * Delete all records.
     */
    public function deleteAll()
    {
        ResignRecord::truncate();
        return redirect()->route('resign.records')->with('success', 'All resign records deleted.');
    }

    /**
     * Display a listing of the records.
     */
    public function records(Request $request)
    {
        $query = ResignRecord::query();

        // Search functionality
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
        $resignRecords = $query->paginate(30);

        return view('resign.records', compact('resignRecords'));
    }

    /**
     * Update an existing record.
     */
    public function updateAccount(Request $request, $id)
    {
        $record = ResignRecord::findOrFail($id);
        $record->update($request->all());

        return redirect()->route('resign.records')->with('success', 'Resign record updated.');
    }

    /**
     * Export records to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new ResignExport, 'resign_records.xlsx');
    }

    /**
     * Import records from Excel.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new ResignImport, $request->file('file'));

        return back()->with('success', 'Resign records imported successfully!');
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('resign.create');
    }

    /**
     * Delete a specific record.
     */
    public function destroy($id)
    {
        $record = ResignRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('resign.records')->with('success', 'Record deleted successfully!');
    }

    /**
     * Show the edit form.
     */
    public function edit($id)
    {
        $ResignRecord = ResignRecord::findOrFail($id);
        return view('resign.edit', compact('ResignRecord'));
    }

    /**
     * Update a specific record.
     */
    public function update(Request $request, $id)
    {
        $record = ResignRecord::findOrFail($id);

        $request->validate([
            'position' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
        ]);

        $record->update($request->all());

        return redirect()->route('resign.records')->with('success', 'Record updated successfully!');
    }
}
