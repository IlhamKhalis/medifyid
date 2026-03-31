<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Kategori - {{ $kategori->kode }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info-kategori { margin-bottom: 20px; }
        
        /* Styling Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        
        /* Styling Footer agar selalu di bawah */
        @page { margin: 100px 25px 50px 25px; } /* Atas, Kanan, Bawah, Kiri */
        footer { 
            position: fixed; 
            bottom: -30px; 
            left: 0px; 
            right: 0px; 
            height: 30px; 
            font-size: 12px; 
            font-style: italic;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <footer>
        Dicetak pada: {{ $waktu_cetak }}
    </footer>

    <div class="header">
        <h2>Detail Master Kategori</h2>
    </div>

    <div class="info-kategori">
        <p><strong>Kode Kategori :</strong> {{ $kategori->kode }}</p>
        <p><strong>Nama Kategori :</strong> {{ $kategori->nama }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Supplier</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori->masterItems as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->supplier }}</td>
                    <td>Rp {{ number_format($item->harga_beli + ($item->harga_beli * $item->laba / 100), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada item yang masuk ke kategori ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>