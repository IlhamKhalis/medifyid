<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    var start_date = '';
    var end_date = '';
    var data_per_fetch = 500;
    var data_fetched = 0;

    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
        });
        getData()
    });

    $('.btn-get-data').click(function() {
        getData()
    })

    function getData(){
        
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        var filter_kode = $('#filter-kode').val()
        var filter_nama = $('#filter-nama').val()
        var filter_harga_min = $('#filter-harga-min').val()
        var filter_harga_max = $('#filter-harga-max').val()
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{url("master-items/search")}}',
            dataType: 'json',
            tryCount: 0,
            retryLimit: 3,
            data: 'kode=' + filter_kode + '&nama=' + filter_nama + '&hargamin=' + filter_harga_min + '&hargamax=' + filter_harga_max,
            success: function(results) {
                var data = results.data

                $.each(data, function(index, item) {
                    var array_temp = [];
                    var harga_jual = item.harga_beli + (item.harga_beli * item.laba / 100);
                    harga_jual = Math.round(harga_jual);

                    var html_view = `<a href="{{url('master-items/view/')}}/` + item.kode + `" class="btn btn-primary">View</a>`;
                    
                    var foto_html = item.foto 
                        ? `<img src="{{ asset('uploads/master_items') }}/` + item.foto + `" alt="foto" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">` 
                        : `<span class="text-muted">Tidak ada foto</span>`;

                    var kategori_html = '';
                    if(item.kategories && item.kategories.length > 0) {
                        var cat_names = [];
                        $.each(item.kategories, function(i, cat) {
                            cat_names.push('<span class="badge bg-secondary">' + cat.nama + '</span>');
                        });
                        kategori_html = cat_names.join(' ');
                    } else {
                        kategori_html = '-';
                    }

                    array_temp.push(item.kode);
                    array_temp.push(item.nama);
                    array_temp.push(kategori_html);
                    array_temp.push(item.harga_beli);
                    array_temp.push(harga_jual);
                    array_temp.push(item.supplier);
                    array_temp.push(foto_html);
                    array_temp.push(html_view);

                    dataTableObj.row.add(array_temp).draw(false);
                });
                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
                alert('Terjadi kesalahan server, tidak dapat mengambil data')
                $('#loading-filter').hide();

                return;
            }
        })
    }
</script>