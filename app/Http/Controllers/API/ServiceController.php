<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Services;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $query = Services::query();

        if($search) {
            $query->where(function($q) use ($search) {
                $q  ->where('jenis_layanan',  'LIKE', "%{$search}%")
                    ->orWhere('harga', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhere('catatan', 'LIKE', "%{$search}%");
            });
        }

        $services = $query->latest()->paginate($perPage);

        if($search) {
            $services->appends(['search' => $search]);
        }
        
        if($request->has('per_page')) {
            $services->appends(['per_page' => $perPage]);
        }

        return response()->json([
            "services" => $services,
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
            'jenis_layanan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,tidak aktif',
            'catatan' => 'nullable|string',
        ]);

        $service = Services::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil ditambahkan.',
            'data' => $service
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = Services::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $service = Services::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Services::findOrFail($id);

        $validated = $request->validate([
            'jenis_layanan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,tidak aktif',
            'catatan' => 'nullable|string',
        ]);

        $service->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil diperbarui',
            'data' => $service
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Services::findOrFail($id);
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil dihapus'
        ]);
    }
}
