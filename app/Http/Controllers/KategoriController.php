<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori.index.index');
    }

    public function formView($method, $id = 0)
    {
        if ($method === 'new') {
            $category = new Kategori();
        } else {
            $category = Kategori::findOrFail($id);
        }

        return view('kategori.form.index', compact('category', 'method'));
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if ($method === 'new') {
            $category = new Kategori();
            $lastId = Kategori::max('id') ?? 0;
            $category->kode = str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $category = Kategori::findOrFail($id);
        }

        $category->nama = $request->nama;
        $category->save();

        return redirect('categories');
    }

    public function delete($id)
    {
        $category = Kategori::findOrFail($id);
        $category->delete();

        return redirect('categories');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $query = Kategori::query();

        if (!empty($kode)) {
            $query->where('kode', $kode);
        }
        
        if (!empty($nama)) {
            $query->where('nama', 'LIKE', '%' . $nama . '%');
        }

        $data = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function singleView($id){
        $category = Kategori::with('masterItems')->findOrFail($id);
        return view('kategori.single.index', compact('category'));
    }

    public function downloadPdf($id)
    {
        $kategori = Kategori::with('masterItems')->findOrFail($id);
        
        $waktu_cetak = Carbon::now()->format('d-m-Y H:i:s');
        
        $pdf = Pdf::loadView('kategori.pdf.index', compact('kategori', 'waktu_cetak'));
        
        return $pdf->download('Data_Kategori_' . $kategori->kode . '.pdf');
    }
}
