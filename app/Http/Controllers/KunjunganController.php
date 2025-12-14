<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Patients;
use App\Models\Poliklinik;
use App\Models\Visits;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    /**
     * Halaman data pending (aksi: pending)
     */
    public function pending(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Visits::with(['patient','doctor','poliklinik'])
            ->whereIn('aksi', ['pending', 'approved']); // Ubah ini!

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($p) use ($search) {
                    $p->where('nama', 'LIKE', "%{$search}%");
                })->orWhereHas('doctor', function($d) use ($search) {
                    $d->where('nama', 'LIKE', "%{$search}%");
                })->orWhere('tanggal_kunjungan', 'LIKE', "%{$search}%")
                ->orWhere('aksi', 'LIKE', "%{$search}%");
            });
        }

        $kunjungan = $query->latest()->paginate($perPage);

        if ($search) $kunjungan->appends(['search' => $search]);
        if ($request->has('per_page')) $kunjungan->appends(['per_page' => $perPage]);

        return view('kunjungan.pending', compact('kunjungan', 'search', 'perPage'));
    }


    /**
     * Halaman data approved (aksi: approved)
     */
    public function approved(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Visits::with(['patient','doctor','poliklinik'])
            ->where('aksi', 'approved');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($p) use ($search) {
                    $p->where('nama', 'LIKE', "%{$search}%");
                })->orWhereHas('doctor', function($d) use ($search) {
                    $d->where('nama', 'LIKE', "%{$search}%");
                })->orWhere('tanggal_kunjungan', 'LIKE', "%{$search}%")
                ->orWhere('aksi', 'LIKE', "%{$search}%");
            });
        }

        $kunjungan = $query->latest()->paginate($perPage);

        if ($search) $kunjungan->appends(['search' => $search]);
        if ($request->has('per_page')) $kunjungan->appends(['per_page' => $perPage]);

        return view('kunjungan.approved', compact('kunjungan', 'search', 'perPage'));
    }


    /**
     * Halaman data not approved
     */
    public function notApproved(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Visits::with(['patient','doctor','poliklinik'])
            ->where('aksi', 'not approved');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($p) use ($search) {
                    $p->where('nama', 'LIKE', "%{$search}%");
                })->orWhereHas('doctor', function($d) use ($search) {
                    $d->where('nama', 'LIKE', "%{$search}%");
                })->orWhere('tanggal_kunjungan', 'LIKE', "%{$search}%")
                ->orWhere('aksi', 'LIKE', "%{$search}%");
            });
        }

        $kunjungan = $query->latest()->paginate($perPage);

        if ($search) $kunjungan->appends(['search' => $search]);
        if ($request->has('per_page')) $kunjungan->appends(['per_page' => $perPage]);

        return view('kunjungan.not-approved', compact('kunjungan', 'search', 'perPage'));
    }

    /**
     * Form membuat data
     */
    public function create()
    {
        $pasien = Patients::all();
        $dokter = Doctors::all();
        $poliklinik = Poliklinik::all();

        return view('kunjungan.create', compact('pasien', 'dokter', 'poliklinik'));
    }

    /**
     * Simpan data
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'poliklinik_id' => 'required',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required',
            'Alasan' => 'nullable'
        ]);

        Visits::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'poliklinik_id' => $request->poliklinik_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'Alasan' => $request->Alasan,
            'status' => 'aktif',          // default
            'aksi' => 'pending'           // default
        ]);

        return redirect()->route('kunjungan.pending')->with('success', 'Kunjungan berhasil dibuat');
    }


    public function approve($id) {
        $visit = Visits::findOrFail($id);
        $visit->update(['aksi' => 'approved']);
        return redirect()->route('kunjungan.pending')->with('success', 'Kunjungan berhasil disetujui');
    }

    public function reject($id) {
        $visit = Visits::findOrFail($id);
        $visit->update(['aksi' => 'not approved']);
        return redirect()->route('kunjungan.pending')->with('success', 'Kunjungan barhasil ditolak');
    }

    public function cancelApproval($id)
    {
        $visit = Visits::findOrFail($id);
        $visit->update(['aksi' => 'pending']);
        return redirect()->route('kunjungan.pending')->with('success', 'Persetujuan kunjungan berhasil dibatalkan');
    }

    public function approveKembali($id)
    {
        $visit = Visits::findOrFail($id);
        $visit->update(['aksi' => 'pending']);
        return redirect()->route('kunjungan.not-approved')->with('success', 'Kunjungan berhasil dikembalikan ke status pending');
    }


    /**
     * Edit data
     */
    public function edit(string $id)
    {
        $kunjungan = Visits::findOrFail($id);
        $pasien = Patients::all();
        $dokter = Doctors::all();
        $poliklinik = Poliklinik::all();

        return view('kunjungan.edit', compact('kunjungan', 'pasien', 'dokter', 'poliklinik'));
    }

    /**
     * Update data
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'poliklinik_id' => 'required',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required',
            'Alasan' => 'nullable',
            'status' => 'required|in:aktif,tidak aktif',
            'aksi' => 'required|in:pending,approved,not approved'
        ]);

        $kunjungan = Visits::findOrFail($id);

        $kunjungan->update([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'poliklinik_id' => $request->poliklinik_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'Alasan' => $request->Alasan,
            'status' => $request->status,
            'aksi' => $request->aksi
        ]);

        return redirect()->route('kunjungan.pending')
            ->with('success', 'Data kunjungan berhasil diperbarui');
    }

    /**
     * Hapus data
     */
    public function destroy(string $id)
    {
        $kunjungan = Visits::findOrFail($id);
        $kunjungan->delete();

        return redirect()->route('kunjungan.pending')
            ->with('success', 'Data kunjungan berhasil dihapus');
    }
}
