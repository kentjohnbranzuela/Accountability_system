<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TechniciansExport;
use App\Imports\TechnicianImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TechnicianController extends Controller
{
    public function create()
    {
        return view('technician.create');
    }

    public function records(Request $request)
    {
        $query = Technician::query();

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

        // Fetch records
        $technicians = $query->paginate(19);

        // Find duplicate serial numbers
        $duplicateSerNos = Technician::select('ser_no')
    ->whereNotNull('ser_no')
    ->groupBy('ser_no')
    ->havingRaw('COUNT(ser_no) > 1')
    ->pluck('ser_no')
    ->toArray(); // Convert Collection to plain array

return view('technician.records', compact('technicians', 'duplicateSerNos'));
    }
    public function store(Request $request)
{
    $request->validate([
        'position.*' => 'required|string|max:255',
        'name.*' => 'required|string|max:255',
        'date.*' => 'required|date',
        'quantity.*' => 'nullable|integer|min:0',
        'ser_no.*' => 'nullable|string|max:255',
        'status.*' => 'nullable|string|max:255',
    ]);

    $technicians = [];

    foreach ($request->position as $key => $position) {
        $technicians[] = [
            'position' => $position,
            'name' => $request->name[$key],
            'date' => $request->date[$key],
            'quantity' => $request->quantity[$key] ?? 0,
            'description' => $request->description[$key] ?? '',
            'ser_no' => $request->ser_no[$key] ?? 'N/A',
            'status' => $request->status[$key] ?? 'N/A',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    Technician::insert($technicians); // Bulk insert for better performance

    return back()->with('success', 'Technician records saved successfully!');
}
    public function edit($id)
    {
        $record = Technician::findOrFail($id);
        return view('technician.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $record = Technician::findOrFail($id);

        $request->validate([
            'id_number' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
        ]);

        $record->update($request->all());

        return redirect()->route('technician.records')->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        $record = Technician::findOrFail($id);
        $record->delete();

        return redirect()->route('technician.records')->with('success', 'Record deleted successfully!');
    }
    public function importExcel(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Import the file
        Excel::import(new TechnicianImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Imported Successfully!');
    }
    public function exportExcel()
{
    return Excel::download(new TechniciansExport, 'technicians.xls');
}
public function deleteAll()
{
    Technician::truncate(); // Deletes all records but keeps the table structure
    return redirect()->back()->with('success', 'All records have been deleted successfully.');
}
}

