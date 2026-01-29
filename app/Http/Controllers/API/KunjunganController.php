<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Medications;
use App\Models\Patients;
use App\Models\Poliklinik;
use App\Models\VisitDetails;
use App\Models\Visits;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KunjunganController extends Controller
{
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

        return response()->json([
            "success" => true,
            "kunjungan" => $kunjungan,
            "search" => $search,
            "perPage" => $perPage
        ], 200);
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

        return response()->json([
            "success" => true,
            "kunjungan" => $kunjungan,
            "search" => $search,
            "perPage" => $perPage
        ], 200);
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

        return response()->json([
            "success" => true,
            "kunjungan" => $kunjungan,
            "search" => $search,
            "perPage" => $perPage
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'patients'   => Patients::all(),
                'doctors'    => Doctor::with("poliklinik")->get(),
                'poliklinik' => Poliklinik::all(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'poliklinik_id'     => 'required|exists:polikliniks,id',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan'   => 'required',
            'Alasan'            => 'nullable|string'
        ]);

        $visit = Visits::create([
            'patient_id'        => $request->patient_id,
            'doctor_id'         => $request->doctor_id,
            'poliklinik_id'     => $request->poliklinik_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_kunjungan'   => $request->waktu_kunjungan,
            'Alasan'            => $request->Alasan,
            'status'            => 'aktif',
            'aksi'              => 'pending'
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kunjungan berhasil dibuat',
            'data'    => $visit
        ], 201);
    }

    public function approve($id){
        $visit = Visits::findOrFail($id);
        $visit->update(['aksi' => 'approved']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kunjungan berhasil disetujui'
        ]);
    }

    public function reject($id){
        $visit = Visits::findOrFail($id);
        $visit->update(['aksi' => 'not approved']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kunjungan berhasil ditolak'
        ]);
    }

    public function cancelApproval($id){
        $visit = Visits::findOrFail($id);
        $visit->update(['aksi' => 'pending']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Persetujuan dibatalkan'
        ]);
    }

    public function approveKembali($id)
    {
        $visit = Visits::findOrFail($id);
        
        $visit->update(['aksi' => 'pending']);
        
        return response()->json([
            'status'  => 'success',
            'message' => 'Kunjungan berhasil dikembalikan ke status pending'
        ]);
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
    public function edit($id)
    {
        $kunjungan = Visits::with(['patient','doctor','poliklinik'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'kunjungan' => $kunjungan,
                'patients' => Patients::all(),
                'doctors' => Doctor::all(),
                'poliklinik' => Poliklinik::all(),
            ]
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'poliklinik_id'     => 'required|exists:polikliniks,id',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan'   => 'required',
            'Alasan'            => 'nullable|string',
            'status'            => 'required|in:aktif,tidak aktif',
            'aksi'              => 'required|in:pending,approved,not approved'
        ]);

        $kunjungan = Visits::findOrFail($id);
        $kunjungan->update($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Data kunjungan berhasil diperbarui',
            'data'    => $kunjungan
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kunjungan = Visits::findOrFail($id);
        $kunjungan->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data kunjungan berhasil dihapus'
        ]);
    }


    public function checkReportStatus($visitId)
    {
        $visit = Visits::with(['patient', 'doctor', 'details'])->findOrFail($visitId);

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
            return response()->json([
                'success' => false,
                'message' => 'Laporan medis belum dibuat untuk kunjungan ini.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'visit' => $visit,
            'detail' => $detail,
        ]);
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
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan medis belum dibuat untuk kunjungan ini.'
                ], 404);
            }

            $data = [
                'visit' => $visit,
                'detail' => $detail,
                'doctor' => $visit->doctor,
                'tanggal' => now()->format('d F Y'),
            ];

            $pdf = PDF::loadView('admin.cetak', $data);

            $filename = "Laporan-Kunjungan-{$visit->patient->nama}-" . now()->format('YmdHis') . ".pdf";

            return $pdf->download($filename);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kunjungan tidak ditemukan.'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error downloading report: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendownload laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kunjunganDokter(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'doctor') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $doctor = $user->doctor;

        if (!$doctor) {
            return response()->json(['message' => 'Data dokter tidak ditemukan'], 403);
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

        return response()->json([
            "visits" => $visits,
            "search" => $search
        ], 200);
    }

    public function buatLaporan(Request $request,$visitId)
    {
        $user = $request->user();
        $doctor = $user->doctor;

        $visit = Visits::where('doctor_id', $doctor->id)
            ->where('aksi', 'approved')
            ->with(['patient', 'poliklinik'])
            ->findOrFail($visitId);

        $medications = Medications::orderBy('nama')->get();

        return response()->json([
            'visit' => $visit,
            'medications' => $medications,
        ]);
    }

    public function storeLaporan(Request $request, $visitId)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'layanan' => 'required|string',
            'notes' => 'nullable|string',
            'medications.*.id' => 'required|exists:medications,id',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.aturan_pakai' => 'required|string',
        ]);

        $doctor = $request->user()->doctor;

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

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil disimpan',
                'detail' => $detail,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function editLaporan(Request $request,$visitId)
    {
        $doctor = $request->user()->doctor;

        $visit = Visits::where('doctor_id', $doctor->id)
            ->where('aksi', 'approved')
            ->findOrFail($visitId);

        $detail = VisitDetails::where('visit_id', $visit->id)
            ->with('medications')
            ->firstOrFail();

        $medications = Medications::orderBy('nama')->get();

        return response()->json([
            'visit' => $visit,
            'detail' => $detail,
            'medications' => $medications,
        ]);
    }

    public function updateLaporan(Request $request, $visitId)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'layanan' => 'required|string',
            'notes' => 'nullable|string',
            'medications.*.id' => 'required|exists:medications,id',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.aturan_pakai' => 'required|string',
        ]);

        $doctor = $request->user()->doctor;

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
                'layanan' => $request->layanan,
                'notes' => $request->notes,
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

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil diperbarui',
                'detail' => $detail,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function viewLaporan(Request $request, $visitId)
    {
        $doctor = $request->user()->doctor;

        $visit = Visits::where('doctor_id', $doctor->id)
            ->where('aksi', 'approved')
            ->with(['patient', 'details.medications'])
            ->findOrFail($visitId);

        $detail = VisitDetails::where('visit_id', $visit->id)
            ->with('medications')
            ->firstOrFail();

        return response()->json([
            'visit' => $visit,
            'detail' => $detail,
        ]);
    }

    public function downloadLaporan(Request $request, $visitId)
    {
        $doctor = $request->user()->doctor;

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

        $pdf = PDF::loadView('dokter.DetailKunjungan.cetak', $data);

        $filename = "Laporan-Kunjungan-{$visit->patient->nama}-" . now()->format('YmdHis') . ".pdf";

        return $pdf->download($filename);
    }

}
