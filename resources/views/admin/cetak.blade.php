<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan - {{ $visit->patient->nama }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            font-size: 14px;
            background-color: #f3f4f6;
            padding: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #4f46e5;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .info-label {
            color: #6b7280;
            font-size: 11px;
        }
        .info-value {
            font-weight: 600;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        .table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 11px;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            display: inline-block;
            margin-top: 50px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #d1d5db;
            padding: 5px;
        }
        .clinic-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .clinic-name {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        .clinic-address {
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Header dengan informasi klinik -->
    <div class="clinic-info">
        <div class="clinic-name">KLINIK SEHAT MANDIRI</div>
        <div class="clinic-address">Jl. Kesehatan No. 123, Kota Sehat | Telp: (021) 123-4567</div>
    </div>

    <!-- Header -->
    <div class="header">
        <h1 style="font-size: 18px; margin-bottom: 5px;">LAPORAN KUNJUNGAN PASIEN</h1>
        <p style="color: #6b7280; font-size: 11px;">Dokumen Resmi Medis</p>
    </div>

    <!-- Patient Info -->
    <div class="section">
        <div class="section-title">INFORMASI PASIEN</div>
        <div class="info-grid">
            <div>
                <div class="info-label">Nama Pasien</div>
                <div class="info-value">{{ $visit->patient->nama ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="info-label">Tanggal Kunjungan</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->format('d F Y') }}</div>
            </div>
            <div>
                <div class="info-label">Keluhan Utama</div>
                <div class="info-value">{{ $visit->Alasan ?? '-' }}</div>
            </div>
            <div>
                <div class="info-label">Waktu Kunjungan</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($visit->waktu_kunjungan)->format('H:i') }}</div>
            </div>
            @if($visit->patient->tanggal_lahir ?? false)
            <div>
                <div class="info-label">Tanggal Lahir</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($visit->patient->tanggal_lahir)->format('d/m/Y') }}</div>
            </div>
            @endif
            @if($visit->patient->nomor_identitas ?? false)
            <div>
                <div class="info-label">Nomor Identitas</div>
                <div class="info-value">{{ $visit->patient->nomor_identitas }}</div>
            </div>
            @endif
            <div>
                <div class="info-label">Poliklinik</div>
                <div class="info-value">{{ $visit->poliklinik->nama_poli ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="info-label">Dokter Penanggung Jawab</div>
                <div class="info-value">{{ $doctor->nama ?? $visit->doctor->nama ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Diagnosis -->
    <div class="section">
        <div class="section-title">DIAGNOSIS & PERAWATAN</div>
        <div>
            <div class="info-label">Diagnosis</div>
            <div class="info-value" style="margin-bottom: 10px;">{{ $detail->diagnosis }}</div>
            
            <div class="info-label">Layanan yang Diberikan</div>
            <div class="info-value" style="margin-bottom: 10px;">{{ $detail->layanan }}</div>
            
            @if($detail->notes)
            <div class="info-label">Catatan Tambahan</div>
            <div class="info-value">{{ $detail->notes }}</div>
            @endif
        </div>
    </div>

    <!-- Medications -->
    @if($detail->medications->count() > 0)
    <div class="section">
        <div class="section-title">RESEP OBAT</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Nama Obat</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 15%;">Harga Satuan</th>
                    <th style="width: 30%;">Aturan Pakai</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalHarga = 0;
                @endphp
                @foreach($detail->medications as $index => $medication)
                    @php
                        $hargaSatuan = $medication->harga ?? 0;
                        $jumlah = $medication->pivot->quantity ?? 0;
                        $subtotal = $hargaSatuan * $jumlah;
                        $totalHarga += $subtotal;
                    @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $medication->nama }}</td>
                    <td style="text-align: center;">{{ $jumlah }}</td>
                    <td style="text-align: right;">Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                    <td>{{ $medication->pivot->aturan_pakai }}</td>
                </tr>
                @endforeach
            </tbody>
            @if($totalHarga > 0)
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">Total Biaya Obat:</td>
                    <td colspan="2" style="text-align: right; font-weight: bold;">
                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    @endif

    <!-- Informasi Tambahan -->
    <div class="section">
        <div class="section-title">INFORMASI TAMBAHAN</div>
        <div class="info-grid">
            <div>
                <div class="info-label">Tanggal Pembuatan Laporan</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($detail->created_at)->format('d F Y H:i') }}</div>
            </div>
            <div>
                <div class="info-label">Status Kunjungan</div>
                <div class="info-value">{{ $visit->status == 'aktif' ? 'Aktif' : 'Selesai' }}</div>
            </div>
        </div>
    </div>

    <!-- Signature -->
    <div class="signature">
        <div style="margin-bottom: 20px; text-align: left;">
            <div class="info-label">Tempat, Tanggal</div>
            <div class="info-value">Kota Sehat, {{ now()->format('d F Y') }}</div>
        </div>
        <div class="signature-line"></div>
        <div style="margin-top: 5px; font-weight: bold;">{{ $doctor->nama ?? $visit->doctor->nama ?? 'N/A' }}</div>
        <div style="font-size: 10px; color: #6b7280;">Dokter Penanggung Jawab</div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan ini dicetak pada {{ $tanggal }} | Dokumen ini sah dan dapat digunakan sebagai bukti medis
    </div>
</body>
</html>