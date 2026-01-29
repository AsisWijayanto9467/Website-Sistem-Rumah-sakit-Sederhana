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
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $query = Patients::with('user');

        if($search) {
            $query->where(function($q) use ($search) {
                $q  ->where('nama',  'LIKE', "%{$search}")
                    ->orWhere('kota', 'LIKE', "%{$search}")
                    ->orWhere('nomor_telpon', 'LIKE', "%{$search}")
                    ->orWhere('nomor_identitas', 'LIKE', "%{$search}")
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        $patients = $query->latest()->paginate($perPage);
        if($search) {
            $patients->appends(['search' => $search]);
        }
        if($request->has('per_page')) {
            $patients->appends(['per_page' => $perPage]);
        }

        return view('pasien.index', compact('patients', 'search'));
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
            'nama'                => 'required|string|max:100',
            'email'               => 'required|email|unique:users,email',
            'password'            => 'required|confirmed|min:6',
            'nomor_telpon'        => 'nullable|string',
            'gender'              => 'nullable|in:laki-laki,perempuan',
            'tipe_darah'          => 'nullable|string',
            'tanggal_lahir'       => 'nullable|date',
            'alamat'              => 'required|string',
            'tanggal_registrasi'  => 'nullable|date',
            'waktu_daftar'        => 'nullable|date_format:H:i',
            'kota'                => 'nullable|string',
            'nomor_identitas'     => 'nullable|string',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => 'patient',
            'password' => Hash::make($request->password)
        ]);

        Patients::create([
            'user_id'           => $user->id,
            'nama'              => $request->nama,
            'nomor_telpon'      => $request->nomor_telpon,
            'gender'            => $request->gender,
            'tipe_darah'        => $request->tipe_darah,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'alamat'            => $request->alamat,
            'tanggal_registrasi'=> $request->tanggal_registrasi ?? now(),
            'waktu_daftar'      => $request->waktu_daftar 
                                    ? now()->format('Y-m-d') . ' ' . $request->waktu_daftar 
                                    : now(),
            'kota'              => $request->kota,
            'nomor_identitas'   => $request->nomor_identitas,
        ]);

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil ditambahkan');
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
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'password'              => 'nullable|conifrmed|min:6',
            'nomor_telpon'          => 'nullable|string',
            'gender'                => 'nullable|in:laki-laki,perempuan',
            'tipe_darah'            => 'nullable|string',
            'tanggal_lahir'         => 'nullable|date',
            'alamat'                => 'required|string',
            'tanggal_registrasi'    => 'nullable|date',
            'waktu_daftar'        => 'nullable|date_format:H:i',
            'kota'                => 'nullable|string',
            'nomor_identitas'     => 'nullable|string',
        ]);

        $user->nama  = $request->nama;
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
            'waktu_daftar'      => $request->waktu_daftar 
                                    ? now()->format('Y-m-d') . ' ' . $request->waktu_daftar 
                                    : now(),
            'kota'              => $request->kota,
            'nomor_identitas'   => $request->nomor_identitas,
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

        return redirect()->route('pasien.index')
            ->with('success', 'Pasien berhasil dihapus beserta user akunnya.');
    }
}
