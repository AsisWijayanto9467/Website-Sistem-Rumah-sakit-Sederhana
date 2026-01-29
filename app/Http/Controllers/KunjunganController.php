<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Medications;
use App\Models\Patients;
use App\Models\Poliklinik;
use App\Models\VisitDetails;
use App\Models\Visits;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $dokter = Doctor::all();
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
            'status' => 'aktif',          
            'aksi' => 'pending'           
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
        $dokter = Doctor::all();
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


    public function checkReportStatus($visitId)
    {
        $visit = Visits::with(['patient', 'doctor', 'details'])
            ->findOrFail($visitId);

        // Cek apakah laporan sudah dibuat
        $hasReport = $visit->details ? true : false;
        
        return response()->json([
            'hasReport' => $hasReport,
            'visitId' => $visitId,
            'patientName' => $visit->patient->nama ?? 'N/A',
            'doctorName' => $visit->doctor->nama ?? 'N/A'
        ]);
    }


    public function viewAdminLaporan($visitId)
    {
        $visit = Visits::where('aksi', 'approved')
            ->with(['patient', 'details.medications'])
            ->findOrFail($visitId);

        $detail = VisitDetails::where('visit_id', $visit->id)
            ->with('medications')
            ->first();

        if (!$detail) {
            return redirect()->back()
                ->with('error', 'Laporan medis belum dibuat untuk kunjungan ini.');
        }

        return view('admin.laporan', compact('visit', 'detail'));
    }

    public function downloadAdminLaporan($visitId)
    {
        try {
            $visit = Visits::where('aksi', 'approved')
                ->with(['patient', 'doctor', 'details.medications'])
                ->findOrFail($visitId);

            $detail = VisitDetails::where('visit_id', $visit->id)
                ->with('medications')
                ->first();

            if (!$detail) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Laporan medis belum dibuat untuk kunjungan ini.'
                    ], 404);
                }
                
                return redirect()->back()
                    ->with('error', 'Laporan medis belum dibuat untuk kunjungan ini.');
            }

            $data = [
                'visit' => $visit,
                'detail' => $detail,
                'doctor' => $visit->doctor, 
                'tanggal' => now()->format('d F Y'),
            ];

            $pdf = Pdf::loadView('admin.cetak', $data);
            
            $filename = "Laporan-Kunjungan-{$visit->patient->nama}-" . now()->format('YmdHis') . ".pdf";
            
            return $pdf->download($filename);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Kunjungan tidak ditemukan.');
                
        } catch (\Exception $e) {
            Log::error('Error downloading report: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mendownload laporan: ' . $e->getMessage());
        }
    }


    public function kunjunganDokter(Request $request) {
        $user = auth()->user();

        if ($user->role !== 'doctor') {
            abort(403, 'Akses ditolak');
        }

        $doctor = $user->doctor;

        if (!$doctor) {
            abort(403, 'Data dokter tidak ditemukan');
        }

        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Visits::with(['patient.user', 'poliklinik', 'details'])
            ->where('doctor_id', $doctor->id)
            ->where('aksi', 'approved'); 
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tanggal_kunjungan', 'LIKE', "%{$search}%")
                ->orWhere('waktu_kunjungan', 'LIKE', "%{$search}%")
                ->orWhereHas('patient', function ($p) use ($search) {
                    $p->where('nama', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('patient.user', function ($u) use ($search) {
                    $u->where('email', 'LIKE', "%{$search}%");
                });
            });
        }

        $visits = $query->latest()->paginate($perPage);

        if ($search) {
            $visits->appends(['search' => $search]);
        }
        if ($request->has('per_page')) {
            $visits->appends(['per_page' => $perPage]);
        }

        return view('dokter.DetailKunjungan.index', compact('visits', 'search'));
    }


    public function buatLaporan($visitId) {
        $user = auth()->user();
        $doctor = $user->doctor;

        $visit  = visits::where('doctor_id', $doctor->id) ->where('aksi', 'approved')->findOrFail($visitId);

        $medications = Medications::orderBy('nama')->get();

        return view('dokter.DetailKunjungan.create', compact('visit', 'medications'));
    }

    public function storeLaporan(Request $request, $visitId) {
        $request->validate([
            'diagnosis' => 'required|string',
            'layanan'   => 'required|string',
            'notes'     => 'nullable|string',
            'medications.*.id' => 'required|exists:medications,id',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.aturan_pakai' => 'required|string',
        ]);

        $doctor = auth()->user()->doctor;

        DB::beginTransaction();

        try {
            $visit = Visits::where('doctor_id', $doctor->id)
                ->where('aksi', 'approved')
                ->findOrFail($visitId);

            $detail = VisitDetails::create([
                'visit_id' => $visit->id,
                'diagnosis' => $request->diagnosis,
                'layanan' => $request->layanan,
                'notes' => $request->notes,
            ]);

            foreach ($request->medications as $med) {
                $medication = Medications::lockForUpdate()->findOrFail($med['id']);

                if ($medication->stock < $med['quantity']) {
                    throw new \Exception(
                        "Stok obat {$medication->nama} tidak mencukupi"
                    );
                }

                $detail->medications()->attach($medication->id, [
                    'quantity' => $med['quantity'],
                    'aturan_pakai' => $med['aturan_pakai'],
                ]);

                $medication->decrement('stock', $med['quantity']);
            }

            DB::commit();

            return redirect()
                ->route('dokter.kunjungan')
                ->with('success', 'Laporan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ])->withInput();
        }
    }

    public function editLaporan($visitId)
    {
        $doctor = auth()->user()->doctor;

        $visit = Visits::where('doctor_id', $doctor->id)
            ->where('aksi', 'approved')
            ->findOrFail($visitId);

        $detail = VisitDetails::where('visit_id', $visit->id)
            ->with('medications')
            ->firstOrFail();

        $medications = Medications::orderBy('nama')->get();

        return view('dokter.DetailKunjungan.edit', compact(
            'visit',
            'detail',
            'medications'
        ));
    }


    public function updateLaporan(Request $request, $visitId)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'layanan'   => 'required|string',
            'notes'     => 'nullable|string',
            'medications.*.id' => 'required|exists:medications,id',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.aturan_pakai' => 'required|string',
        ]);

        $doctor = auth()->user()->doctor;

        DB::beginTransaction();

        try {
            $visit = Visits::where('doctor_id', $doctor->id)
                ->where('aksi', 'approved')
                ->findOrFail($visitId);

            $detail = VisitDetails::where('visit_id', $visit->id)
                ->with('medications')
                ->firstOrFail();

            foreach ($detail->medications as $oldMed) {
                $oldMed->increment('stock', $oldMed->pivot->quantity);
            }

            $detail->update([
                'diagnosis' => $request->diagnosis,
                'layanan'   => $request->layanan,
                'notes'     => $request->notes,
            ]);

            $detail->medications()->detach();

            foreach ($request->medications as $med) {
                $medication = Medications::lockForUpdate()->findOrFail($med['id']);

                if ($medication->stock < $med['quantity']) {
                    throw new \Exception(
                        "Stok obat {$medication->nama} tidak mencukupi"
                    );
                }

                $detail->medications()->attach($medication->id, [
                    'quantity' => $med['quantity'],
                    'aturan_pakai' => $med['aturan_pakai'],
                ]);

                $medication->decrement('stock', $med['quantity']);
            }

            DB::commit();

            return redirect()
                ->route('dokter.kunjungan')
                ->with('success', 'Laporan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ])->withInput();
        }
    }

    public function viewLaporan($visitId)
    {
        $doctor = auth()->user()->doctor;

        $visit = Visits::where('doctor_id', $doctor->id)
            ->where('aksi', 'approved')
            ->with(['patient', 'details.medications'])
            ->findOrFail($visitId);

        $detail = VisitDetails::where('visit_id', $visit->id)
            ->with('medications')
            ->firstOrFail();

        return view('dokter.DetailKunjungan.show', compact('visit', 'detail'));
    }

    public function downloadLaporan($visitId)
    {
        $doctor = auth()->user()->doctor;

        $visit = Visits::where('doctor_id', $doctor->id)
            ->where('aksi', 'approved')
            ->with(['patient', 'details.medications'])
            ->findOrFail($visitId);

        $detail = VisitDetails::where('visit_id', $visit->id)
            ->with('medications')
            ->firstOrFail();

        $data = [
            'visit' => $visit,
            'detail' => $detail,
            'doctor' => $doctor,
            'tanggal' => now()->format('d F Y'),
        ];

        $pdf = Pdf::loadView('dokter.DetailKunjungan.cetak', $data);
        
        $filename = "Laporan-Kunjungan-{$visit->patient->nama}-" . now()->format('YmdHis') . ".pdf";
        
        return $pdf->download($filename);
    }
}
