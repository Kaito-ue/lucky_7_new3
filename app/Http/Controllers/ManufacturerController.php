<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:manufacturers,name|max:255',
        ]);

        Manufacturer::create([
            'name' => $request->name,
        ]);

        return redirect()->route('route_name')->with('success', 'Manufacturer created successfully.');
    }
}
