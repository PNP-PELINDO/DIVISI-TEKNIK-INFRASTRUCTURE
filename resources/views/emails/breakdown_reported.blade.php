<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden; }
        .header { background: #003366; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #777; }
        .badge { background: #ff4444; color: white; padding: 5px 10px; rounded: 5px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #eee; }
        th { color: #888; font-size: 12px; text-transform: uppercase; }
        .btn { display: inline-block; background: #003366; color: white !important; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin-top: 25px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Pemberitahuan Kerusakan Aset</h2>
        </div>
        <div class="content">
            <p>Halo Tim Teknik,</p>
            <p>Telah dilaporkan kerusakan baru pada infrastruktur operasional. Segera lakukan pengecekan dan tindak lanjut.</p>
            
            <table>
                <tr>
                    <th>Kode Alat</th>
                    <td><strong>{{ $log->infrastructure->code_name }}</strong></td>
                </tr>
                <tr>
                    <th>Jenis / Tipe</th>
                    <td>{{ $log->infrastructure->type }}</td>
                </tr>
                <tr>
                    <th>Terminal / Lokasi</th>
                    <td>{{ $log->infrastructure->entity->name }}</td>
                </tr>
                <tr>
                    <th>Detail Kendala</th>
                    <td>{{ $log->issue_detail }}</td>
                </tr>
                <tr>
                    <th>Dilaporkan Oleh</th>
                    <td>{{ $log->createdBy->name }}</td>
                </tr>
                <tr>
                    <th>Tanggal Laporan</th>
                    <td>{{ $log->created_at->format('d M Y | H:i') }}</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ route('admin.breakdowns.index') }}" class="btn">Lihat Detail di Portal</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} DIVISI TEKNIK - Pelindo Infrastructure Reporting System</p>
        </div>
    </div>
</body>
</html>
