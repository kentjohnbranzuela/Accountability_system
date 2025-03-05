<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountabilityRecord;

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
        $records = $query->paginate(5);

        return view('accountability.accountability_records', compact('records'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'id_number' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
            'quantity' => 'required|integer',
            'description' => 'required|string',
            'ser_no' => 'required|string|unique:accountability_records,ser_no',
            'status' => 'required|string',
        ]);

        // Insert into database
        AccountabilityRecord::create($request->all());

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
            'quantity' => 'required|integer',
            'description' => 'required|string',
            'ser_no' => 'required|string',
            'status' => 'required|string',
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
}
