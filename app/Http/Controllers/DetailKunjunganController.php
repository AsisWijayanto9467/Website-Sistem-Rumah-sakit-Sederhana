<?php

namespace App\Http\Controllers;

use App\Models\Medications;
use App\Models\Visit_Details;
use App\Models\Visits;
use Illuminate\Http\Request;

class DetailKunjunganController extends Controller
{
    public function index($visit_id)
    {
        $visit = Visits::with('patient','doctor','service')->findOrFail($visit_id);
        $details = Visit_Details::with('medication')
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

        Visit_Details::create([
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
        $detail = Visit_Details::findOrFail($id);
        $visit = $detail->visit;
        $medications = Medications::all();

        return view('dokter.Detail.edit', compact('detail','visit','medications'));
    }


    public function update(Request $request, $id)
    {
        $detail = Visit_Details::findOrFail($id);

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
        $detail = Visit_Details::findOrFail($id);
        $visit_id = $detail->visit_id;
        $detail->delete();

        return redirect()->route('detailKunjungan.index', $visit_id)
            ->with('success', 'Detail berhasil dihapus');
    }
}
