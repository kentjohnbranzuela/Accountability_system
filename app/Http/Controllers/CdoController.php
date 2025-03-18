<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cdo;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CdoExport;
use App\Imports\CdoImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CdoController extends Controller
{
    public function create()
    {
        return view('cdos.create');
    }

    public function records(Request $request)
    {
        $query = Cdo::query();
    
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
        $cdos = $query->paginate(19);
    
        // Find duplicate serial numbers
        $duplicateSerNos = Cdo::select('ser_no')
            ->whereNotNull('ser_no')
            ->groupBy('ser_no')
            ->havingRaw('COUNT(ser_no) > 1')
            ->pluck('ser_no')
            ->toArray();

        return view('cdos.records', compact('cdos', 'duplicateSerNos'));
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'position' => 'required|string',
        'name' => 'required|string',
        'date' => 'nullable|date',
        'quantity' => 'nullable|integer',
        'description' => 'nullable|string',
        'ser_no' => 'nullable|string',
        'status' => 'nullable|string',
    ]);

    // Assign "N/A" if any field is empty
    $cdo = Cdo::create([
        'position' => $request->position ?? 'N/A',
        'name' => $request->name ?? 'N/A',
        'date' => $request->date ?? now(), // Defaults to today if empty
        'quantity' => $request->quantity ?? 0, // Default to 0 if empty
        'description' => $request->description ?? 'N/A',
        'ser_no' => $request->ser_no ?? 'N/A',
        'status' => $request->status ?? 'N/A',
    ]);

    return redirect()->route('cdos.records')->with('success', 'Record added successfully!');
}

    public function edit($id)
    {
        $record = Cdo::findOrFail($id);
        return view('cdos.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $record = Cdo::findOrFail($id);

        $request->validate([
            'position' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
        ]);

        $record->update($request->all());

        return redirect()->route('cdos.records')->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        $record = Cdo::findOrFail($id);
        $record->delete();

        return redirect()->route('cdos.records')->with('success', 'Record deleted successfully!');
    }

    public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    \Log::info('Import function triggered!');

    try {
        Excel::import(new CdoImport, $request->file('file'));
        \Log::info('Import successful!');
        return redirect()->back()->with('success', 'Data Imported Successfully!');
    } catch (\Exception $e) {
        \Log::error('Import failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Import failed! Check logs for details.');
    }
}
    public function export()
{
    return Excel::download(new CdoExport, 'cdo_records.xlsx');
}
    public function deleteAll()
{
    Cdo::truncate(); // Deletes all records

    return redirect()->route('cdos.records')->with('success', 'All records have been deleted!');
}
public function checkData()
{
    $hasData = Cdo::exists(); // ✅ Check if the table has any records

    return response()->json(['hasData' => $hasData]);
}

}
