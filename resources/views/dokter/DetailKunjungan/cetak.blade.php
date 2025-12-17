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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1 style="font-size: 18px; margin-bottom: 5px;">LAPORAN KUNJUNGAN PASIEN</h1>
        <p style="color: #6b7280;">Rumah Sakit / Klinik Anda</p>
    </div>

    <!-- Patient Info -->
    <div class="section">
        <div class="section-title">INFORMASI PASIEN</div>
        <div class="info-grid">
            <div>
                <div class="info-label">Nama Pasien</div>
                <div class="info-value">{{ $visit->patient->nama }}</div>
            </div>
            <div>
                <div class="info-label">Tanggal Kunjungan</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($visit->date)->format('d F Y') }}</div>
            </div>
            <div>
                <div class="info-label">Keluhan Utama</div>
                <div class="info-value">{{ $visit->reason }}</div>
            </div>
            <div>
                <div class="info-label">Dokter Penanggung Jawab</div>
                <div class="info-value">{{ $doctor->nama }}</div>
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
                    <th style="width: 40%;">Nama Obat</th>
                    <th style="width: 20%;">Jumlah</th>
                    <th style="width: 40%;">Aturan Pakai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail->medications as $medication)
                <tr>
                    <td>{{ $medication->nama }}</td>
                    <td>{{ $medication->pivot->quantity }}</td>
                    <td>{{ $medication->pivot->aturan_pakai }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Signature -->
    <div class="signature">
        <div class="signature-line"></div>
        <div style="margin-top: 5px; font-weight: bold;">{{ $doctor->nama }}</div>
        <div style="font-size: 10px; color: #6b7280;">Dokter Penanggung Jawab</div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan ini dicetak pada {{ $tanggal }} | Dokumen ini sah dan dapat digunakan sebagai bukti medis
    </div>
</body>
</html>