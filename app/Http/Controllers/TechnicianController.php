<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician;
use Maatwebsite\Excel\Facades\Excel;
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
        'serial_no' => 'nullable|string|max:255', // Make sure this matches your field name
    ]);

    // Ensure field names match your database table
    Technician::create([
        'position' => $request->Position,
        'name' => $request->name,
        'date' => $request->date,
        'quantity' => $request->quantity,
        'description' => $request->description,
        'ser_no' => $request->serial_no, // Map 'serial_no' from form to 'ser_no' in DB
        'status' => $request->status,
    ]);

    return back()->with('success', 'Technician record saved!');
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
            'quantity' => 'required|integer',
            'description' => 'required|string',
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
}

