<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Medications;
use App\Models\VisitDetails;
use App\Models\Visits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailKunjunganController extends Controller
{
    public function index($visit_id)
    {
        $visit = Visits::with('patient', 'doctor')
            ->findOrFail($visit_id);

        $details = VisitDetails::with('medications')
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
        $visit = Visits::with('patient', 'doctor')->findOrFail($visit_id);

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
            'medication_id' => 'required|exists:medications,id',
            'quantity'      => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $visit_id) {

            $medication = Medications::findOrFail($request->medication_id);

            $medication->decreaseStock($request->quantity);

            VisitDetails::create([
                'visit_id'      => $visit_id,
                'diagnosis'     => $request->diagnosis,
                'layanan'       => $request->layanan,
                'notes'         => $request->notes,
                'medication_id' => $medication->id,
                'quantity'      => $request->quantity,
            ]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail kunjungan berhasil ditambahkan'
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
        $detail = VisitDetails::with('medications')->findOrFail($id);

        $request->validate([
            'diagnosis'     => 'required|string',
            'layanan'       => 'nullable|string',
            'notes'         => 'nullable|string',
            'medication_id' => 'required|exists:medications,id',
            'quantity'      => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $detail) {
            $oldMedication = $detail->medications->first();

            if (!$oldMedication) {
                throw new \Exception('Data obat lama tidak ditemukan');
            }

            $oldQty = $oldMedication->pivot->quantity;

            $newMedication = Medications::lockForUpdate()
                ->findOrFail($request->medication_id);

            $newQty = $request->quantity;

            if ($oldMedication->id !== $newMedication->id) {
                $oldMedication->increaseStock($oldQty);
                $newMedication->decreaseStock($newQty);

                $detail->medications()->sync([
                    $newMedication->id => [
                        'quantity' => $newQty
                    ]
                ]);

            } else {
                $diff = $newQty - $oldQty;

                if ($diff > 0) {
                    $newMedication->decreaseStock($diff);
                } elseif ($diff < 0) {
                    $newMedication->increaseStock(abs($diff));
                }

                $detail->medications()->updateExistingPivot(
                    $newMedication->id,
                    ['quantity' => $newQty]
                );
            }

            $detail->update([
                'diagnosis' => $request->diagnosis,
                'layanan'   => $request->layanan,
                'notes'     => $request->notes,
            ]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail kunjungan berhasil diperbarui'
        ]);
    }


    /**
     * DELETE detail kunjungan
     */
    public function destroy($id)
    {
        $detail = VisitDetails::findOrFail($id);

        DB::transaction(function () use ($detail) {

            if ($detail->medication_id && $detail->quantity) {
                $medication = Medications::find($detail->medication_id);
                $medication->increaseStock($detail->quantity);
            }

            $detail->delete();
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail kunjungan berhasil dihapus'
        ]);
    }
}
