<div class="col-sm-12">
    <div class="card">
        <div class="card-header pb-0">
            <h5>Filter</h5>
        </div>
        <div class="card-body">
            @if(count($data))
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th>Lampiran</th>
                        <th>Tanggal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->kategori_nilai }}</td>
                            <td>{{ $item->nilai }}</td>
                            <td>{{ $item->desc_nilai ?? '-' }}</td>
                            <td>
                                @if($item->lampiran)
                                    <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank">Lihat
                                        File</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
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
