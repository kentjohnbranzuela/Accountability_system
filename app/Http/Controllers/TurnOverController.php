<?php

namespace App\Http\Controllers;
use App\Models\TurnOver;
use App\Exports\TurnOverExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Imports\TurnOverImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TurnOverController extends Controller
{
    /**
     * Display a listing of the resource.
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
    TurnOver::create($validatedData);

    return redirect()->route('turnover.records')->with('success', 'Turn Over record added.');
}   public function deleteAll()
{
    TurnOver::truncate();
    return redirect()->route('turnover.records')->with('success', 'All Turn Over records deleted.');
}
// Show All Records
public function records()
{
    $turnovers = TurnOver::paginate(10); // Paginate the results, change 10 to desired items per page
    return view('turnover.records', compact('turnovers'));
}
// Update Account
public function updateAccount(Request $request, $id)
{
    $turnover = TurnOver::findOrFail($id);
    $turnover->update($request->all());

    return redirect()->route('turnover.index')->with('success', 'Turn Over record updated.');
}
// Export Excel
public function exportExcel()
{
    return Excel::download(new TurnOverExport, 'turnovers.xlsx');
}

// Import Excel
 public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new TurnOverImport, $request->file('file'));

    return back()->with('success', 'TurnOver records imported successfully!');
}
public function create  (){
    $turnover = new TurnOver;
    return view('turnover.create', compact('turnover'));

}
public function destroy($id)
    {
        $record = TurnOver::findOrFail($id);
        $record->delete();

        return redirect()->route('turnover.records')->with('success', 'Record deleted successfully!');
    }
    public function edit($id)
    {
        $record = TurnOver::findOrFail($id);
        return view('turnover.edit', compact('record'));
    }
    public function update(Request $request, $id)
    {
        $record = TurnOver::findOrFail($id);

        $request->validate([
            'position' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
        ]);

        $record->update($request->all());

        return redirect()->route('turnover.records')->with('success', 'Record updated successfully!');
    }

}
