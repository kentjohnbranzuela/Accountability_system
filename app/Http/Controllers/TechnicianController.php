<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician;

class TechnicianController extends Controller
{
    public function records()
    {
        $technicians = Technician::all(); // Fetch all technicians
        return view('technician.records', compact('technicians'));
    }    

    public function create()
    {
        return view('technician.create');
    }

    public function store(Request $request)
{
    $technician = Technician::create([
        'Position' => $request->Position,
        'name' => $request->name,
        'date' => $request->date,
        'quantity' => $request->quantity,
        'description' => $request->description,
        'ser_no' => $request->serial_no,  // Use correct column name
        'status' => $request->status,
    ]);

    return redirect()->route('technician.records')->with('success', 'Technician added successfully!');
}
    public function show(Technician $technician)
    {
        return view('technician.show', compact('technician'));
    }

    public function edit(Technician $technician)
    {
        return view('technician.edit', compact('technician'));
    }

    public function update(Request $request, Technician $technician)
    {
        $request->validate([
            'Position' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
            'quantity' => 'required|integer',
            'description' => 'required|string',
            'ser_no' => 'required|string|unique:technicians,ser_no,' . $technician->id, // Fixed table name
            'status' => 'required|string',
        ]);

        $technician->update($request->all());

        return redirect()->route('technician.records')->with('success', 'Technician updated successfully.');
    }

    public function destroy(Technician $technician)
    {
        $technician->delete();
        return redirect()->route('technician.records')->with('success', 'Technician deleted successfully.');
    }
}
