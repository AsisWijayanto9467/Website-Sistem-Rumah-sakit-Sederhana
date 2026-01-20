<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

        return response()->json([
            "success" => true,
            "medications" => $medications,
            "search" => $search
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'harga'     => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'stock'     => 'required|integer'
        ]);

        $obat = Medications::create([
            'nama'      => $request->nama,
            'harga'     => $request->harga,
            'deskripsi' => $request->deskripsi,
            'stock'     => $request->stock
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data obat berhasil ditambahkan',
            'data'    => $obat
        ], 201);
    }

    /**
     * Detail obat (by ID)
     */
    public function show($id)
    {
        $obat = Medications::find($id);

        if (!$obat) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data obat tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $obat
        ]);
    }

    /**
     * Update data obat
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'harga'     => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'stock'     => 'required|integer'
        ]);

        $obat = Medications::find($id);

        if (!$obat) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data obat tidak ditemukan'
            ], 404);
        }

        $obat->update([
            'nama'      => $request->nama,
            'harga'     => $request->harga,
            'deskripsi' => $request->deskripsi,
            'stock'     => $request->stock
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data obat berhasil diperbarui',
            'data'    => $obat
        ]);
    }

    /**
     * Hapus data obat
     */
    public function destroy($id)
    {
        $obat = Medications::find($id);

        if (!$obat) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data obat tidak ditemukan'
            ], 404);
        }

        $obat->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data obat berhasil dihapus'
        ]);
    }
}
