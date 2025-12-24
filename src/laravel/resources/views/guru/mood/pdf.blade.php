<!DOCTYPE html>
<html>
<head>
    <title>Laporan Mood Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            background-color: #f4f4f4;
            padding: 5px;
            border-left: 4px solid #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .info-table td {
            border: none;
            padding: 4px;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            color: #fff;
            display: inline-block;
        }
        .bg-normal { background-color: #28a745; }
        .bg-ringan { background-color: #17a2b8; }
        .bg-sedang { background-color: #ffc107; color: #333; }
        .bg-parah { background-color: #fd7e14; }
        .bg-sangat-parah { background-color: #dc3545; }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Monitoring Kesehatan Mental Siswa</h2>
        <p>Depresiku - Sistem Monitoring Depresi Siswa</p>
    </div>

    <div class="section-title">Informasi Siswa</div>
    <table class="info-table">
        <tr>
            <td width="150">Nama Lengkap</td>
            <td width="10">:</td>
            <td>{{ $siswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>:</td>
            <td>{{ $siswa->nisn }}</td>
        </tr>
        <tr>
            <td>Periode Laporan</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->subDays(13)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    @if($dassScores)
    <div class="section-title">Hasil Asesmen DASS-21 Terakhir</div>
    <p style="font-size: 10px; margin-bottom: 5px;">Tanggal Pengisian: {{ $dassScores['date'] }}</p>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Skor</th>
                <th>Tingkat Keparahan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Depresi</td>
                <td>{{ $dassScores['depression'] }}</td>
                <td>{{ $dassScores['depression_label'] }}</td>
            </tr>
            <tr>
                <td>Kecemasan (Anxiety)</td>
                <td>{{ $dassScores['anxiety'] }}</td>
                <td>{{ $dassScores['anxiety_label'] }}</td>
            </tr>
            <tr>
                <td>Stres</td>
                <td>{{ $dassScores['stress'] }}</td>
                <td>{{ $dassScores['stress_label'] }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="section-title">Riwayat Mood & Presensi (14 Hari Terakhir)</div>
    <table>
        <thead>
            <tr>
                <th width="80">Tanggal</th>
                <th width="50">Waktu</th>
                <th width="50">Status</th>
                <th width="100">Prediksi Mood</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($moodHistory as $history)
            <tr>
                <td>{{ $history['tanggal'] }}</td>
                <td>{{ $history['waktu'] }}</td>
                <td>{{ $history['status'] }}</td>
                <td>
                    {{ $history['emotion_emoji'] }} {{ ucfirst($history['emotion_label']) }}
                </td>
                <td>{{ $history['catatan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>
</html>
