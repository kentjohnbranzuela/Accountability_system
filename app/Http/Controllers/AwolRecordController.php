<?php

namespace App\Http\Controllers;

use App\Models\AwolRecord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AwolImport;
use App\Exports\AwolExport;

class AwolRecordController extends Controller {
    public function records(Request $request)
    {
        $query = AwolRecord::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('position', 'LIKE', "%$search%")
                  ->orWhere('name', 'LIKE', "%$search%")
                  ->orWhere('description', 'LIKE', "%$search%")
                  ->orWhere('ser_no', 'LIKE', "%$search%");
            });
        }

        // Fetch records with pagination
        $awols = $query->paginate(40);

        // Find duplicate serial numbers
        $duplicateSerNos = AwolRecord::select('ser_no')
            ->whereNotNull('ser_no')
            ->groupBy('ser_no')
            ->havingRaw('COUNT(ser_no) > 1')
            ->pluck('ser_no')
            ->toArray();

        return view('awol.records', compact('awols', 'duplicateSerNos'));
    }


    public function create() {
        return view('awol.create');
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'position.*' => 'required|string',
        'name.*' => 'required|string',
        'date.*' => 'nullable|date',
        'quantity.*' => 'nullable|integer',
        'description.*' => 'nullable|string',
        'ser_no.*' => 'nullable|string',
        'status.*' => 'nullable|string',
    ]);

    // Loop through the arrays and insert multiple records
    foreach ($request->position as $index => $position) {
        AwolRecord::create([
            'position' => $position ?? 'N/A',
            'name' => $request->name[$index] ?? 'N/A',
            'date' => $request->date[$index] ?? now(),
            'quantity' => $request->quantity[$index] ?? 0,
            'description' => $request->description[$index] ?? 'N/A',
            'ser_no' => $request->ser_no[$index] ?? 'N/A',
            'status' => $request->status[$index] ?? 'N/A',
        ]);
    }

    return redirect()->route('awol.records')->with('success', 'Records added successfully!');
}


    public function show(AwolRecord $awolRecord) {
    return view('awol.show', compact('awolRecord')); // Ensure 'awol.show' view exists
}

    public function edit(AwolRecord $awolRecord) {
        return view('awol.edit', compact('awolRecord'));
    }

    public function update(Request $request, $id)
    {
        $record = AwolRecord::findOrFail($id);

        $request->validate([
            'position' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
        ]);

        $record->update($request->all());

        return redirect()->route('awol.records')->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        $record = AwolRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('awol.records')->with('success', 'Record deleted successfully!');
    }
     public function deleteAll()
{
    AwolRecord::truncate(); // Deletes all records

    return redirect()->route('awol.records')->with('success', 'All records have been deleted!');
}
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xls,xlsx,csv|max:2048'
    ]);

    try {
        Excel::import(new AwolImport, $request->file('file'));

        return redirect()->back()->with('success', 'AWOL records imported successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
    }
}
    public function exportExcel()
{
    return Excel::download(new AwolExport, 'awol_records.xlsx');
}
}
