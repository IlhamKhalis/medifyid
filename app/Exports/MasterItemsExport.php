<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    // 1. Ambil data dari database (termasuk relasi kategories)
    public function collection()
    {
        return MasterItem::with('kategories')->orderBy('id', 'desc')->get();
    }

    // 2. Buat Header / Judul Kolom (Sesuai Soal No 5)
    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori (terpisah koma)',
            'Nama Items',
            'Nama Supplier',
            'Harga',
            'Laba',
            'Harga Jual'
        ];
    }

    // 3. Masukkan data ke masing-masing kolom
    public function map($item): array
    {
        $this->rowNumber++;
        
        // Menggabungkan nama kategori dengan koma (Sesuai Soal)
        $nama_kategori = $item->kategories->pluck('nama')->implode(', ');
        
        // Menghitung harga jual (Harga beli + Laba)
        $harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

        return [
            $this->rowNumber,
            $nama_kategori,
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba . ' %',
            round($harga_jual)
        ];
    }
}