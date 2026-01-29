<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Poliklinik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Doctor::with(['user', 'poliklinik']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_telpon', 'LIKE', "%{$search}%")
                  ->orWhereHas('poliklinik', function($p) use ($search) {
                      $p->where('nama_poli', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $docters = $query->latest()->paginate($perPage);

        if ($search) $docters->appends(['search' => $search]);
        if ($request->has('per_page')) $docters->appends(['per_page' => $perPage]);

        return view('dokter.index', compact('docters', 'search'));
    }

    public function showPasien(Request $request) {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            abort(403, 'Akses ditolak');
        }

        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = $doctor->patients()
            ->with('user')
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_telpon', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('email', 'LIKE', "%{$search}%");
                    });
                }
            });

        $patients = $query->latest()->paginate($perPage);

        if ($search) {
            $patients->appends(['search' => $search]);
        }
        if ($request->has('per_page')) {
            $patients->appends(['per_page' => $perPage]);
        }

        return view('dokter.Pasien.index', compact('patients', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polikliniks = Poliklinik::where('status', 'aktif')->get();
        return view('dokter.create', compact('polikliniks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'nama'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|confirmed|min:6',
            'poliklinik_id'    => 'required|exists:polikliniks,id',
            'tarif_konsultasi' => 'required|integer',
            'lama_pengalaman'  => 'nullable|integer',
            'pendidikan'       => 'nullable|string',
            'nomor_telpon'     => 'nullable|string',
            'status'           => 'required|in:aktif,tidak aktif'
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'role'     => 'doctor',
            'nomor_telpon' => $request->nomor_telpon,
            'password' => Hash::make($request->password),
        ]);

        Doctor::create([
            'user_id'         => $user->id,
            'nama'            => $request->nama,
            'poliklinik_id'   => $request->poliklinik_id,
            'tarif_konsultasi'=> $request->tarif_konsultasi,
            'lama_pengalaman' => $request->lama_pengalaman,
            'pendidikan'      => $request->pendidikan,
            'nomor_telpon'    => $request->nomor_telpon,
            'status'          => $request->status,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dokter = Doctor::with('user')->findOrFail($id);
        return view('dokter.show', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dokter = Doctor::with('user')->findOrFail($id);
        $polikliniks = Poliklinik::where('status', 'aktif')->get();
        return view('dokter.edit', compact('dokter', 'polikliniks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dokter = Doctor::with('user')->findOrFail($id);
        $user = $dokter->user;

        $request->validate([
            'nama'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'password'         => 'nullable|confirmed|min:6',
            'poliklinik_id'    => 'required|exists:polikliniks,id',
            'tarif_konsultasi' => 'required|integer',
            'lama_pengalaman'  => 'nullable|integer',
            'pendidikan'       => 'nullable|string',
            'nomor_telpon'     => 'nullable|string',
            'status'           => 'required|in:aktif,tidak aktif'
        ]);

        /** Update User */
        $user->update([
            'nama'  => $request->nama,
            'email' => $request->email,
            'password' => $request->password 
                ? Hash::make($request->password)
                : $user->password,
        ]);

        /** Update Data Dokter */
        $dokter->update([
            'nama'            => $request->nama,
            'poliklinik_id'   => $request->poliklinik_id,
            'tarif_konsultasi'=> $request->tarif_konsultasi,
            'lama_pengalaman' => $request->lama_pengalaman,
            'pendidikan'      => $request->pendidikan,
            'nomor_telpon'    => $request->nomor_telpon,
            'status'          => $request->status,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokter = Doctor::findOrFail($id);
        $user = $dokter->user;

        $dokter->delete();
        $user->delete();

        return redirect()->route('dokter.index')->with('success', 'Anda Berhasil menghapus User Dokter');
    }
}
