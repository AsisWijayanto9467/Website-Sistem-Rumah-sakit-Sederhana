<?php

namespace App\Http\Controllers;

use App\Models\Medications;
use App\Models\VisitDetails;
use App\Models\Visits;
use Illuminate\Http\Request;

class DetailKunjunganController extends Controller
{
    public function index($visit_id)
    {
        $visit = Visits::with('patient','doctor','service')->findOrFail($visit_id);
        $details = VisitDetails::with('medication')
            ->where('visit_id', $visit_id)
            ->latest()
            ->get();

        return view('dokter.Detail.edit', compact('visit','details'));
    }


    public function create($visit_id)
    {
        $visit = Visits::with('patient','doctor')->findOrFail($visit_id);
        $medications = Medications::all();

        return view('dokter.Detail.create', compact('visit','medications'));
    }


    public function store(Request $request, $visit_id)
    {
        $request->validate([
            'diagnosis' => 'required',
            'layanan' => 'nullable',
            'notes' => 'nullable',
            'medication_id' => 'nullable|exists:medications,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        VisitDetails::create([
            'visit_id' => $visit_id,
            'diagnosis' => $request->diagnosis,
            'layanan' => $request->layanan,
            'notes' => $request->notes,
            'medication_id' => $request->medication_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('detailkunjungan.store', $visit_id)
            ->with('success', 'Detail kunjungan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $detail = VisitDetails::findOrFail($id);
        $visit = $detail->visit;
        $medications = Medications::all();

        return view('dokter.Detail.edit', compact('detail','visit','medications'));
    }


    public function update(Request $request, $id)
    {
        $detail = VisitDetails::findOrFail($id);

        $request->validate([
            'diagnosis' => 'required',
            'layanan' => 'nullable',
            'notes' => 'nullable',
            'medication_id' => 'nullable|exists:medications,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $detail->update([
            'diagnosis' => $request->diagnosis,
            'layanan' => $request->layanan,
            'notes' => $request->notes,
            'medication_id' => $request->medication_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('detailKunjungan.index', $detail->visit_id)
            ->with('success', 'Detail berhasil diperbarui');
    }

    public function destroy($id)
    {
        $detail = VisitDetails::findOrFail($id);
        $visit_id = $detail->visit_id;
        $detail->delete();

        return redirect()->route('detailKunjungan.index', $visit_id)
            ->with('success', 'Detail berhasil dihapus');
    }
}
