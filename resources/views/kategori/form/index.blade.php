@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ $method === 'new' ? 'Tambah Kategori Baru' : 'Ubah Kategori' }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ url('categories/form/' . $method . ($method === 'edit' ? '/' . $category->id : '')) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Kode Kategori</label>
                            <input type="text" class="form-control" value="{{ $method === 'new' ? 'Otomatis dibuat setelah disimpan' : $category->kode }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $category->nama) }}" required>
                            @error('nama')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ $method === 'new' ? 'Simpan Kategori' : 'Update Kategori' }}</button>
                        <a href="{{ url('categories') }}" class="btn btn-secondary ms-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
