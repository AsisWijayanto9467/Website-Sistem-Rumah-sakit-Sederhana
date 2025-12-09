<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokter = Doctors::with('user')->get();
        return view('dokter.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|confirmed|min:6',
            'spesialisasi' => 'required|string:max:100',
            'lama_pengalaman' => 'nullable|integer',
            'pendidikan' => 'nullable|string',
            'nomor_telpon' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => 'patient',
            'password' => Hash::make($request->password)
        ]); 

        Doctors::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'spesialisasi' => $request->spessialisasi,
            'lama_pengalaman' => $request->lama_pengalaman,
            'pendidikan' => $request->pendidikan,
            'nomor_telpon' => $request->nomor_telpon,
            'status' => $request->status
        ]);

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dokter = Doctors::with('user')->findOrFail($id);
        return view('dokter.show', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dokter = Doctors::with('user')->findOrFail($id);
        return view('dokter.edit', compact('dokter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dokter = Doctors::with('user')->findOrFail($id);
        $user = $dokter->user;

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $user->id,
            'password' => 'required|confirmed|min:6',
            'spesialisasi' => 'required|string:max:100',
            'lama_pengalaman' => 'nullable|integer',
            'pendidikan' => 'nullable|string',
            'nomor_telpon' => 'nullable|string',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        $dokter->user->update([
            'name'  => $request->nama,
            'email' => $request->email,
            'password' => $request->password 
                ? Hash::make($request->password)
                : $dokter->user->password,
        ]);

        $dokter->update([
            'nama'          => $request->nama,
            'spesialisasi'  => $request->spesialisasi,
            'lama_pengalaman'=> $request->lama_pengalaman,
            'pendidikan'    => $request->pendidikan,
            'nomor_telpon'  => $request->nomor_telpon,
            'status'        => $request->status,
        ]);

        return redirect()->route('doctors.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokter = Doctors::findOrFail($id);
        $user = $dokter->user;

        $dokter->delete();
        $user->delete();

        return redirect()->route('dokter.index')->with('success', 'Anda Berhasil menghapus User Dokter');
    }
}
