<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PasienController extends Controller
{
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

        return response()->json([
            "patient" => $patients,
            "search" => $search
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            'nama'     => $request->nama,
            'email'    => $request->email,
            'role'     => 'patient',
            'password' => Hash::make($request->password),
        ]);

        $patient = Patients::create([
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

        return response()->json([
            'status'  => 'success',
            'message' => 'Pasien berhasil ditambahkan',
            'data'    => $patient->load('user')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patients::with('user')->find($id);

        if (!$patient) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data pasien tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $patient
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $patient = Patients::find($id);

        if (!$patient) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data pasien tidak ditemukan'
            ], 404);
        }

        $user = $patient->user;

        $request->validate([
            'nama'                => 'required|string|max:100',
            'email'               => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password'            => 'nullable|confirmed|min:6',
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
            'waktu_daftar'       => $request->waktu_daftar 
                                    ? now()->format('Y-m-d') . ' ' . $request->waktu_daftar 
                                    : now(),
            'kota'               => $request->kota,
            'nomor_identitas'    => $request->nomor_identitas,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data pasien dan user berhasil diperbarui',
            'data'    => $patient->load('user')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $patient = Patients::find($id);

        if (!$patient) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data pasien tidak ditemukan'
            ], 404);
        }

        $user = $patient->user;

        $patient->delete();

        if ($user) {
            $user->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Pasien beserta user berhasil dihapus'
        ]);
    }
}
