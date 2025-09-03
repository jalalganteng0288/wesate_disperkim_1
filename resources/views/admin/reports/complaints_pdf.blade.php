<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; font-size: 12px; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Tanggal Cetak: {{ $date }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul Laporan</th>
                <th>Pelapor</th>
                <th>Lokasi Perumahan</th>
                <th>Status</th>
                <th>Tanggal Dilaporkan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
            <tr>
                <td>{{ $complaint->id }}</td>
                <td>{{ $complaint->judul }}</td>
                <td>{{ $complaint->user->name ?? 'N/A' }}</td>
                <td>{{ $complaint->housingUnit->name ?? 'Umum' }}</td>
                <td>{{ ucfirst($complaint->status) }}</td>
                <td>{{ $complaint->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>