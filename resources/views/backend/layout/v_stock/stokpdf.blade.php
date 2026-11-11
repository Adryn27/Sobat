<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 80px; height: auto; margin-bottom: 10px; }
        .header h1 { margin: 0; text-transform: uppercase; color: #224abe; }
        .header p { margin: 2px 0; font-size: 11px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #4e73df; color: white; padding: 10px; text-align: center; border: 1px solid #ddd; }
        td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        
        .total-box { margin-top: 25px; padding: 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; text-align: right; font-weight: bold; }
        .signature-section { margin-top: 40px; }
        .signature-box { float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>

    <!-- Header dengan Logo -->
    <div class="header">
        <img src="{{ public_path('Img/logo.png') }}" class="logo">
        <h1>APOTEK SAPPHIRE FARMA</h1>
        <p>Bekasi Junction Lt. UG Blok S-SN 354, 354A</p>
        <p>Telepon: 0878-2184-9988 | Email: contact@sapphirefarma.com</p>
    </div>
    <h3>{{ $judul }} - {{ date('d F Y') }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Nama Obat</th>
                <th>Stok Sistem</th>
                <th>Stok Fisik</th>
                <th>Selisih</th>
            </tr>
        </thead>
        <tbody>
            @foreach($index as $o)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $o->kategori->nama_kategori }}</td>
                <td>{{ $o->nama_obat }}</td>
                <td style="text-align: center;">{{ $o->stok }}</td>
                <td></td> <td></td> </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>