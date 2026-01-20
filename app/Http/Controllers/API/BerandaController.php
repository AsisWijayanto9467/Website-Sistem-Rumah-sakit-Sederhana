<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Medications;
use App\Models\Services;
use App\Models\User;
use App\Models\Visits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $totalAdmin = User::where('role', 'admin')->count();
        $totalDokter = User::where('role', 'doctor')->count();
        $totalPasien = User::where('role', 'patient')->count();

        $visitsData = Visits::select(
                DB::raw('MONTH(tanggal_kunjungan) as month'),
                DB::raw('YEAR(tanggal_kunjungan) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->where('tanggal_kunjungan', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $formattedVisitsData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $visit = $visitsData->first(function ($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });

            $formattedVisitsData[] = $visit ? $visit->total : 0;
        }

        $patientsData = User::where('role', 'patient')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $formattedPatientsData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $patient = $patientsData->first(function ($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });

            $formattedPatientsData[] = $patient ? $patient->total : 0;
        }

        $monthLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthLabels[] = now()->subMonths($i)->format('M');
        }

        $totalObat = Medications::count();
        $totalLayanan = Services::count();
        $layananAktif = Services::where('status', 'aktif')->count();

        $totalPendapatan = Services::sum('harga') + Medications::sum('harga');

        // Table kunjungan
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $query = Visits::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($p) use ($search) {
                    $p->where('nama', 'LIKE', "%{$search}%");
                })->orWhereHas('doctor', function ($d) use ($search) {
                    $d->where('nama', 'LIKE', "%{$search}%");
                })->orWhere('tanggal_kunjungan', 'LIKE', "%{$search}%")
                ->orWhere('aksi', 'LIKE', "%{$search}%");
            });
        }

        $kunjungan = $query->latest()->paginate($perPage);

        if ($search) $kunjungan->appends(['search' => $search]);
        if ($request->has('per_page')) $kunjungan->appends(['per_page' => $perPage]);

        return response()->json([
            'totalAdmin' => $totalAdmin,
            'totalDokter' => $totalDokter,
            'totalPasien' => $totalPasien,
            'totalObat' => $totalObat,
            'totalLayanan' => $totalLayanan,
            'layananAktif' => $layananAktif,
            'totalPendapatan' => $totalPendapatan,
            'formattedVisitsData' => $formattedVisitsData,
            'formattedPatientsData' => $formattedPatientsData,
            'monthLabels' => $monthLabels,
            'kunjungan' => $kunjungan,
            'search' => $search,
            'perPage' => $perPage,
        ]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
