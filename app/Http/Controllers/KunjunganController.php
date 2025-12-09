<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Patients;
use App\Models\Services;
use App\Models\Visits;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pending() {
        $kunjungan = Visits::with(['patient','doctor','service'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('kunjungan.pending', compact('kunjungan'));
    }

    public function completed() {
        $kunjungan = Visits::with(['patient','doctor','service'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(10);

        return view('kunjungan.pending', compact('kunjungan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pasien = Patients::all();
        $dokter = Doctors::all();
        $service = Services::all();

        return view('kunjungan.create', compact('pasien', 'dokter', 'service'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'service_id' => 'required',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required',
            'complaint' => 'nullable'
        ]);

        Visits::create([
            'patient_id' => $request->patient_id ,
            'doctor_id' => $request->doctor_id,
            'service_id' => $request->service_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'complaint' => $request->complaint,
            'status' => 'pending'
        ]);

        return redirect()->route('kunjungan.pending')->with('success', 'Kunjungan berhasil Dibuat');
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
        $kunjungan = Visits::findOrFail($id);
        $pasien = Patients::all();
        $dokter = Doctors::all();
        $service = Services::all();

        return view('kunjungan.edit', compact('kunjungan', 'pasien', 'dokter', 'service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'service_id' => 'required',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required',
            'complaint' => 'nullable',
            'status' => 'required'
        ]);

        $kunjungan = Visits::findOrFail($id);

        $kunjungan->update([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'service_id' => $request->service_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'complaint' => $request->complaint,
            'status' => $request->status
        ]);

        return redirect()->route('kunjungan.pending')->with('success', 'Data Kujungan Berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kunjungan = Visits::findOrFail($id);
        $kunjungan->delete();

        return redirect()->route('kunjungan.pending')->with('success', 'Data Kunjungan berhasil dihapus');
    }
}
