<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountabilityRecord;

class AccountabilityRecordsController extends Controller
{

    public function accountability_records(Request $request)
    {
        // Fetch all records with search functionality
        $query = AccountabilityRecord::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('id_number', 'LIKE', "%$search%")
                  ->orWhere('name', 'LIKE', "%$search%")
                  ->orWhere('description', 'LIKE', "%$search%")
                  ->orWhere('ser_no', 'LIKE', "%$search%")
                  ->orWhere('status', 'LIKE', "%$search%");
        }

        $records = $query->get();

        return view('accountability.accountability_records', compact('records'));
    }


    public function store(Request $request)
{
    // Validate the input
    $request->validate([
        'id_number' => 'required|string', // Removed 'unique' rule
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

        return redirect()->route('accountability.index')->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        $record = AccountabilityRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('accountability.index')->with('success', 'Record deleted successfully!');
    }

}
