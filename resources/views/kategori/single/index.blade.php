@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Detail Kategori</div>
        <div class="card-body">
            <h5>Kode: {{ $category->kode }}</h5>
            <h5>Nama Kategori: {{ $category->nama }}</h5>
            <a href="{{ url('categories/view/' . $category->id . '/pdf') }}" class="btn btn-danger mb-3">Download PDF</a>
            
            <hr>
            <h6>Daftar Item dalam Kategori Ini:</h6>
            <ul>
                @forelse($category->masterItems as $item)
                    <li>{{ $item->kode }} - {{ $item->nama }} (Supplier: {{ $item->supplier }})</li>
                @empty
                    <li><i>Belum ada item di kategori ini.</i></li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection