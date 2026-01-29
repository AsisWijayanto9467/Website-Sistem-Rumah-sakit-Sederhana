<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\JamKunjungan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
        /**
     * GET /api/jam-kunjungan
     * List jam kunjungan dokter (search + pagination)
     */
    public function index(Request $request)
    {
        $dokterId = $request->user()->doctor->id;
        
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $query = JamKunjungan::where('dokter_id', $dokterId)->orderBy('jam_mulai');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('jam_mulai', 'LIKE', "%{$search}%")
                  ->orWhere('jam_selesai', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }
        
        $jamKunjungan = $query->paginate($perPage);
        
        if ($search) $jamKunjungan->appends(['search' => $search]);
        if ($request->has('per_page')) $jamKunjungan->appends(['per_page' => $perPage]);
        
        return response()->json([
            "success" => true,
            "jamKunjungan" => $jamKunjungan,
            "search" => $search
        ], 200);
    }

    /**
     * POST /api/jam-kunjungan
     * Create jam kunjungan
     */
    public function store(Request $request)
    {
        $request->validate([
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'status'      => 'required|in:aktif,tidak aktif',
        ]);

        $user = $request->user();

        if ($user->role !== 'doctor') {
            return response()->json([
                'status'  => 'forbidden',
                'message' => 'Akun ini bukan dokter'
            ], 403);
        }

        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return response()->json([
                'status'  => 'not-found',
                'message' => 'Data dokter tidak ditemukan'
            ], 404);
        }

        $jam = JamKunjungan::create([
            'dokter_id'   => $doctor->id,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => $request->status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Jam kunjungan berhasil ditambahkan',
            'data'    => $jam
        ], 201);
    }

    /**
     * GET /api/jam-kunjungan/{id}
     * Detail jam kunjungan
     */
    public function show(Request $request,$id)
    {
        $jam = JamKunjungan::find($id);

        if (!$jam) {
            return response()->json([
                'status'  => 'not-found',
                'message' => 'Jam kunjungan tidak ditemukan'
            ], 404);
        }

        if ($jam->dokter_id !== $request->user()->doctor->id) {
            return response()->json([
                'status'  => 'forbidden',
                'message' => 'Tidak memiliki akses'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $jam
        ]);
    }

    /**
     * PUT /api/jam-kunjungan/{id}
     * Update jam kunjungan
     */
    public function update(Request $request, $id)
    {
        $jam = JamKunjungan::find($id);

        if (!$jam) {
            return response()->json([
                'status'  => 'not-found',
                'message' => 'Jam kunjungan tidak ditemukan'
            ], 404);
        }

        if ($jam->dokter_id !== $request->user()->doctor->id) {
            return response()->json([
                'status'  => 'forbidden',
                'message' => 'Tidak memiliki akses'
            ], 403);
        }

        $request->validate([
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'status'      => 'required|in:aktif,tidak aktif',
        ]);

        $jam->update([
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => $request->status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Jam kunjungan berhasil diperbarui',
            'data'    => $jam
        ]);
    }

    /**
     * DELETE /api/jam-kunjungan/{id}
     * Delete jam kunjungan
     */
    public function destroy(Request $request,$id)
    {
        $jam = JamKunjungan::find($id);

        if (!$jam) {
            return response()->json([
                'status'  => 'not-found',
                'message' => 'Jam kunjungan tidak ditemukan'
            ], 404);
        }

        if ($jam->dokter_id !== $request->user()->doctor->id) {
            return response()->json([
                'status'  => 'forbidden',
                'message' => 'Tidak memiliki akses'
            ], 403);
        }

        $jam->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Jam kunjungan berhasil dihapus'
        ]);
    }
}
