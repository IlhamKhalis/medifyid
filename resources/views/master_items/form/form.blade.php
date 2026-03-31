<form method="POST" enctype="multipart/form-data" action="{{ url('master-items/form/' . $method . ($method !== 'new' ? '/' . $item->id : '')) }}">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    <div class="form-group">
        <label>Jenis</label>
        <input type="text" name="jenis" class="form-control" required value="{{ old('jenis', $item->jenis ?? 'Umum') }}">
        @error('jenis')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Foto</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
    </div>

    @php
        $selectedCategoryIds = old('categories', $item->kategories->pluck('id')->toArray());
    @endphp
    <div class="form-group">
        <label>Kategori Item</label>
        <select name="categories[]" class="form-control" multiple>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" 
                    {{ in_array($cat->id, $selectedCategoryIds) ? 'selected' : '' }}>
                    {{ $cat->kode }} - {{ $cat->nama }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Tahan tombol CTRL (Windows) atau CMD (Mac) untuk memilih lebih dari satu kategori.</small>
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>