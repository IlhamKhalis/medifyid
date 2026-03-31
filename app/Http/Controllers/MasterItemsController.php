<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\Kategori;
use App\Exports\MasterItemsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::with('kategories');

        if ($request->filled('kode')) {
            $data_search = $data_search->where('kode', $kode);
        }

        if ($request->filled('nama')) {
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        if ($request->filled('hargamin')) {
            $data_search = $data_search->whereRaw('(harga_beli + (harga_beli * laba / 100)) >= ?', [$hargamin]);
        }

        if ($request->filled('hargamax')) {
            $data_search = $data_search->whereRaw('(harga_beli + (harga_beli * laba / 100)) <= ?', [$hargamax]);
        }

        $data_search = $data_search->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem();
        } else {
            $item = MasterItem::findOrFail($id);
        }
        
        $data['item'] = $item;
        $data['method'] = $method;
        $data['categories'] = Kategori::all();
        
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'laba' => 'required|numeric|min:0|max:100',
            'supplier' => 'nullable|string|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:kategoris,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $lastId = MasterItem::withTrashed()->max('id') ?? 0;
            $kode = str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $data_item = MasterItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName(); 
            
            $destinationPath = public_path('uploads/master_items');

            if ($method != 'new' && $data_item->foto) {
                $oldFile = $destinationPath . '/' . $data_item->foto;
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            $file->move($destinationPath, $filename);
            
            $data_item->foto = $filename;
        }
        // --------------------------

        $data_item->save();

        if ($request->has('categories')) {
            $data_item->kategories()->sync($request->categories);
        } else {
            $data_item->kategories()->sync([]);
        }

        return redirect('master-items');
    }

    public function delete($id)
    {
        $item = MasterItem::find($id);

        if ($item) {
            if ($item->foto) {
                $imagePath = public_path('uploads/master_items/' . $item->foto);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
            $item->delete();
        }

        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    public function exportExcel()
    {
        return Excel::download(new MasterItemsExport, 'Data_Master_Items.xlsx');
    }
}