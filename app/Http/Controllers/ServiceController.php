<?php

namespace App\Http\Controllers;

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
                $q  ->where('jenis_layanan',  'LIKE', "%{$search}")
                    ->orWhere('harga', 'LIKE', "%{$search}")
                    ->orWhere('status', 'LIKE', "%{$search}")
                    ->orWhere('catatan', 'LIKE', "%{$search}");
            });
        }

        $services = $query->latest()->paginate($perPage);

        if($search) {
            $services->appends(['search' => $search]);
        }
        
        if($request->has('per_page')) {
            $services->appends(['per_page' => $perPage]);
        }

        return view('layanan.service.index', compact('services', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layanan.service.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_layanan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,tidak aktif',
            'catatan' => 'nullable|string'
        ]);

        Services::create($request->all());

        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan.');
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
        $service = Services::findOrFail($id);
        return view('layanan.service.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Services::findOrFail($id);

        $request->validate([
            'jenis_layanan' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak aktif',
            'harga' => 'required|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);

        $service->update($request->all());
        return redirect()->route('services.index')->with('success', 'Layanan Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Services::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Layanan berhasil dihapus');
    }
}
