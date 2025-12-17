<?php

namespace App\Http\Controllers;

use App\Models\Medications;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $query = Medications::query();

        if($search) {
            $query->where(function($q) use ($search) {
                $q  ->where('nama',  'LIKE', "%{$search}%")
                    ->orWhere('harga', 'LIKE', "%{$search}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                    ->orWhere('stock', 'LIKE', "%{$search}%");
            });
        }

        $medications = $query->latest()->paginate($perPage);
        if($search) {
            $medications->appends(['search' => $search]);
        }
        if($request->has('per_page')) {
            $medications->appends(['per_page' => $perPage]);
        }

        return view('layanan.Obat.index', compact('medications', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layanan.obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'stock' => 'required|integer'
        ]);

        Medications::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'stock' => $request->stock
        ]);

        return redirect()->route('medication.index')->with('success', "Data Obat Berhasil Ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $obat = Medications::findOrFail($id);
        return view('layanan.Obat.edit', compact('obat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'stock' => 'required|integer'
        ]);

        $obat  = Medications::findOrFail($id);
        $obat->update([
            'nama'      => $request->nama,
            'harga'     => $request->harga,
            'deskripsi' => $request->deskripsi,
            'stock'     => $request->stock,
        ]);

        return redirect()->route('medication.index')->with('success', 'Data obat berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $obat = Medications::findOrFail($id);
        $obat->delete();

        return redirect()->route('medication.index')->with('success', 'Data Obat Berhasil Dihapus');
    }
}
