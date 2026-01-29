<?php

namespace App\Http\Controllers;

use App\Models\Poliklinik;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $query = Poliklinik::query();

        if($search) {
            $query->where(function($q) use ($search) {
                $q  ->where('nama_poli',  'LIKE', "%{$search}")
                    ->orWhere('deskripsi', 'LIKE', "%{$search}")
                    ->orWhere('status', 'LIKE', "%{$search}");
            });
        }

        $polikliniks = $query->latest()->paginate($perPage);
        if($search) {
            $polikliniks->appends(['search' => $search]);
        }
        if($request->has('per_page')) {
            $polikliniks->appends(['per_page' => $perPage]);
        }

        return view('Poliklinik.index', compact('polikliniks', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('poliklinik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        Poliklinik::create([
            'nama_poli' =>  $request->nama_poli,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status
        ]);

        return redirect()->route('poliklinik.index')->with('success', 'Poliklinik berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $poliklinik = Poliklinik::findOrFail($id);
        return view('poliklinik.show', compact('poliklinik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $poliklinik = Poliklinik::findOrFail($id);
        return view('poliklinik.edit', compact('poliklinik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $poliklinik = Poliklinik::findOrFail($id);

        $request->validate([
            'nama_poli' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        $poliklinik->update($request->all());

        return redirect()->route('poliklinik.index')->with('success', 'Poliklinik berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $poliklinik = Poliklinik::findOrFail($id);
        $poliklinik->delete();

        return redirect()->route('poliklinik.index')->with('success', 'Poliklinik berhasil dihapus');
    }
}
