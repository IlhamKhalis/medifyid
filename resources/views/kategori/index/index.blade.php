@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="form-group mb-2">
                <a href="{{ url('categories/form/new') }}" class="btn btn-secondary btn-sm">+ Kategori Baru</a>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Kategori Items</span>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="filter-kode" class="form-control" placeholder="Filter Kode Kategori">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="filter-nama" class="form-control" placeholder="Filter Nama Kategori">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn-get-data">Cari / Filter</button>
                        </div>
                    </div>

                    <table id="table-kategori" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Kategori</th>
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                    <div id="loading-filter" style="display: none;" class="text-center mt-2">Loading data...</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table-kategori').DataTable({
            searching: false, // Dimatikan karena kita pakai filter kustom
            order: [[0, 'desc']],
        });
        getData(); // Load data pertama kali
    });

    $('.btn-get-data').click(function() {
        getData(); // Load data saat tombol dicari
    });

    function getData(){
        $('#loading-filter').show();
        var dataTableObj = $('#table-kategori').DataTable();
        
        // Ambil nilai dari input filter
        var filter_kode = $('#filter-kode').val();
        var filter_nama = $('#filter-nama').val();
        
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{url("categories/search")}}',
            dataType: 'json',
            data: {
                kode: filter_kode,
                nama: filter_nama
            },
            success: function(results) {
                var data = results.data;
                
                $.each(data, function(index, item) {
                    // Tombol menuju Single View
                    var html_action = `<a href="{{url('categories/view')}}/` + item.id + `" class="btn btn-info text-white btn-sm">View Detail</a>`;
                    
                    var array_temp = [
                        item.kode,
                        item.nama,
                        html_action
                    ];

                    dataTableObj.row.add(array_temp).draw(false);
                });
                $('#loading-filter').hide();
            },
            error: function() {
                alert('Terjadi kesalahan server, tidak dapat mengambil data');
                $('#loading-filter').hide();
            }
        });
    }
</script>
@endsection