<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Medications;
use App\Models\VisitDetails;
use App\Models\Visits;
use Illuminate\Http\Request;

class DetailKunjunganController extends Controller
{
    public function index($visit_id)
    {
        $visit = Visits::with('patient', 'doctor', 'service')
            ->findOrFail($visit_id);

        $details = VisitDetails::with('medication')
            ->where('visit_id', $visit_id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'visit'  => $visit,
            'details'=> $details
        ]);
    }

    /**
     * GET data create (visit + medication list)
     */
    public function create($visit_id)
    {
        $visit = Visits::with('patient', 'doctor')
            ->findOrFail($visit_id);

        $medications = Medications::all();

        return response()->json([
            'status'      => 'success',
            'visit'       => $visit,
            'medications' => $medications
        ]);
    }

    /**
     * STORE detail kunjungan
     */
    public function store(Request $request, $visit_id)
    {
        $request->validate([
            'diagnosis'     => 'required|string',
            'layanan'       => 'nullable|string',
            'notes'         => 'nullable|string',
            'medication_id' => 'nullable|exists:medications,id',
            'quantity'      => 'nullable|integer|min:1',
        ]);

        $detail = VisitDetails::create([
            'visit_id'      => $visit_id,
            'diagnosis'     => $request->diagnosis,
            'layanan'       => $request->layanan,
            'notes'         => $request->notes,
            'medication_id' => $request->medication_id,
            'quantity'      => $request->quantity,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail kunjungan berhasil ditambahkan',
            'data'    => $detail
        ], 201);
    }

    /**
     * GET detail by id
     */
    public function edit($id)
    {
        $detail = VisitDetails::with('visit', 'medication')
            ->findOrFail($id);

        $medications = Medications::all();

        return response()->json([
            'status'      => 'success',
            'detail'      => $detail,
            'medications' => $medications
        ]);
    }

    /**
     * UPDATE detail kunjungan
     */
    public function update(Request $request, $id)
    {
        $detail = VisitDetails::findOrFail($id);

        $request->validate([
            'diagnosis'     => 'required|string',
            'layanan'       => 'nullable|string',
            'notes'         => 'nullable|string',
            'medication_id' => 'nullable|exists:medications,id',
            'quantity'      => 'nullable|integer|min:1',
        ]);

        $detail->update([
            'diagnosis'     => $request->diagnosis,
            'layanan'       => $request->layanan,
            'notes'         => $request->notes,
            'medication_id' => $request->medication_id,
            'quantity'      => $request->quantity,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail kunjungan berhasil diperbarui',
            'data'    => $detail
        ]);
    }

    /**
     * DELETE detail kunjungan
     */
    public function destroy($id)
    {
        $detail = VisitDetails::findOrFail($id);
        $detail->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail kunjungan berhasil dihapus'
        ]);
    }
}
