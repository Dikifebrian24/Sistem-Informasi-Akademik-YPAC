@extends('layouts.app')
@section('content')
    @pushOnce('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
    @endPushOnce
    <style type="text/css">
        #data tr {
            text-align: center;
        }
    </style>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Input Progress {{ $mapel }}</h5>
                                </div>
                                <form class="theme-form" id="f_progress" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="id_siswa">Nama Siswa</label>
                                            <select class="form-control select2" name="id_siswa" id="id_siswa" required>
                                                <option value="">-- Pilih Siswa --</option>
                                                @foreach($siswa as $item)
                                                    <option value="{{ $item->id_siswa }}">{{ $item->nm_siswa }}
                                                        - {{ $item->nisn }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0">Mata Pelajaran</label>
                                            <input type="text" class="form-control" value="{{ $mapel }}" disabled>
                                            <input type="hidden" class="form-control" name="id_mapel"
                                                   value="{{ $id_mapel }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="kategori_nilai">Tanggal</label>
                                            <input type="date" id="tgl_progress" name="tgl_progress"
                                                   class="form-control">
                                        </div>


                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="nilai_bintang">Input Progress</label>
                                            <small>(1 - 10)</small>
                                            <div id="bintang-wrapper"></div>
                                            <input type="text" id="nilai_value" name="nilai_value" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="desc_nilai">Keterangan</label>
                                            <textarea class="form-control" name="desc_nilai" id="desc_nilai" cols="30"
                                                      rows="3"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="lampiran">Lampiran File Progress belajar</label>
                                            <input class="form-control" type="file" name="lampiran" id="lampiran"
                                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Simpan Progress</button>
                                    </div>
                                </form>


                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <script>

        const wrapper = document.getElementById('bintang-wrapper');
        const nilaiInput = document.getElementById('nilai_value');

        let currentRating = 0;

        function renderStars(rating) {
            wrapper.innerHTML = '';
            for (let i = 1; i <= 10; i++) {
                const star = document.createElement('span');
                star.textContent = '★';
                star.style.cursor = 'pointer';
                star.style.fontSize = '40px'; // ukuran besar
                star.style.color = i <= rating ? '#ffc107' : '#e4e5e9';
                star.style.marginRight = '5px';
                star.addEventListener('click', () => {
                    currentRating = i;
                    nilaiInput.value = i; // Nilai disimpan 1–10
                    renderStars(currentRating);
                });
                wrapper.appendChild(star);
            }
        }

        renderStars(currentRating);
    </script>
    @include('dashboard.akademik.progress.js')

@endsection
