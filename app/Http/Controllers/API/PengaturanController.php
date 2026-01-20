<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        return response()->json([
            "message" => "user pengaturan",
            "user" => $user
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nomor_telpon' => 'nullable|string|max:20',
        ]);

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->nomor_telpon = $validated['nomor_telpon'] ?? null;
        $user->save();

        if ($user->role === 'doctor' && $user->doctor) {
            $doctor = $user->doctor;
            $doctor->nama = $validated['nama'];
            $doctor->nomor_telpon = $validated['nomor_telpon'] ?? null;
            $doctor->save();
        }

        if ($user->role === 'patient' && $user->patient) {
            $patient = $user->patient;
            $patient->nama = $validated['nama'];
            $patient->nomor_telpon = $validated['nomor_telpon'] ?? null;
            $patient->save();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui',
            'data'    => $user,
        ]);
    }

    public function ubah_password(Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Password saat ini tidak cocok',
            ], 422);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Password berhasil diperbarui',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
