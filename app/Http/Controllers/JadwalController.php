<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\JamKunjungan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dokterId = auth()->user()->doctor->id;
        
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
        
        return view('dokter.Jadwal.index', compact('jamKunjungan', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokter.Jadwal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'status'      => 'required|in:aktif,tidak aktif',
        ]);
        
        $user = auth()->user();


        if ($user->role !== 'doctor') {
            abort(403, 'Akun ini bukan dokter');
        }

        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            abort(403, 'Data dokter tidak ditemukan');
        }


        JamKunjungan::create([
            'dokter_id'   => $doctor->id,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => $request->status,
        ]);

        return redirect()->route('jadwals.index')->with('success', 'Jam kunjungan berhasil ditambahkan');
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
        $jam = JamKunjungan::findOrFail($id);
        return view('dokter.jadwal.edit', compact('jam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jam = JamKunjungan::findOrFail($id);

        if ($jam->dokter_id !== auth()->user()->doctor->id) {
            abort(403);
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

        return redirect()->route('jadwals.index')->with('success', 'Jam kunjungan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jam = JamKunjungan::findOrFail($id);

        if ($jam->dokter_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $jam->delete();

        return redirect()->back()
            ->with('success', 'Jam kunjungan berhasil dihapus');
    }
}
