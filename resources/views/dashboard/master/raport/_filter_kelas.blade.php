<div class="col-sm-12">
    <div class="card">
        <div class="card-header pb-0">
            <h5>Filter</h5>
        </div>
        <div class="card-body">
            @if(count($data))
                <table id="myTable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->nm_siswa }}</td>
                            <td>{{ $item->nm_kelas }}</td>
                            <td>
                                <a href="{{ route('raport.cetak', $item->id_siswa) }}" class="btn btn-primary btn-sm" target="_blank">
                                    Cetak Raport
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">Belum ada data nilai untuk siswa ini.</div>
            @endif
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            "order": [[3, "desc"]], // Urutkan berdasarkan kolom Tanggal (index 3) descending
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari total _MAX_ data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });

</script>
