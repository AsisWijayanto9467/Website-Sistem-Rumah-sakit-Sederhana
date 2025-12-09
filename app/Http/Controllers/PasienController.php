<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pastients = Patients::with('user')->get();
        return view('pasien.index', compact('pastients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users.email',
            'password'              => 'required|conifrmed|min:6',
            'nomor_telpon'          => 'nullable|string',
            'gender'                => 'nullable|in:laki-laki,perempuan',
            'tipe_darah'            => 'nullable|string',
            'tanggal_lahir'         => 'nullable|date',
            'alamat'                => 'required|string',
            'tanggal_registrasi'    => 'nullable|date'
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => 'patient',
            'password' => Hash::make($request->password)
        ]);

        Patients::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'nomor_telpon' => $request->nomor_telpon,
            'gender' => $request->gender,
            'tipe_darah' => $request->tipe_darah,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'tanggal_registrasi' => $request->tanggal_registrasi ?? now(),
        ]);

        return redirect()->route('pasien.index')->with([
            'success', 'Pasien berhasil Ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patients::with('user')->findOrFail($id);
        return view('pasien.show',compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $patient = Patients::with('user')->findOrFail($id);
        return view('pasien.edit',compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient = Patients::findOrFail($id);
        $user = $patient->user;

        $request->validate([
            'nama'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users.email,' . $user->id,
            'password'              => 'required|conifrmed|min:6',
            'nomor_telpon'          => 'nullable|string',
            'gender'                => 'nullable|in:laki-laki,perempuan',
            'tipe_darah'            => 'nullable|string',
            'tanggal_lahir'         => 'nullable|date',
            'alamat'                => 'required|string',
            'tanggal_registrasi'    => 'nullable|date'
        ]);

        $user->name  = $request->nama;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $patient->update([
            'nama'               => $request->nama,
            'nomor_telpon'       => $request->nomor_telpon,
            'gender'             => $request->gender,
            'tipe_darah'         => $request->tipe_darah,
            'tanggal_lahir'      => $request->tanggal_lahir,
            'alamat'             => $request->alamat,
            'tanggal_registrasi' => $request->tanggal_registrasi,
        ]);

        return redirect()->route('pasien.index')->with(
            'success', 'Data pasien dan user berhasil diperbarui'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patients::findOrFail($id);
        $user = $patient->user;

        $patient->delete();
        $user->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Pasien berhasil dihapus beserta user akunnya.');
    }
}
