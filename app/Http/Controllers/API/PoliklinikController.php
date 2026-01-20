<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

        
        return response()->json([
            "polikliniks" => $polikliniks,
            "search" => $search
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_poli' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        $poliklinik = Poliklinik::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Poliklinik berhasil ditambahkan',
            'data' => $poliklinik
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $poliklinik = Poliklinik::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $poliklinik
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $poliklinik = Poliklinik::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $poliklinik
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $poliklinik = Poliklinik::findOrFail($id);

        $validated = $request->validate([
            'nama_poli' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        $poliklinik->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Poliklinik berhasil diperbarui',
            'data' => $poliklinik
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $poliklinik = Poliklinik::findOrFail($id);
        $poliklinik->delete();

        return response()->json([
            'success' => true,
            'message' => 'Poliklinik berhasil dihapus'
        ]);
    }
}
