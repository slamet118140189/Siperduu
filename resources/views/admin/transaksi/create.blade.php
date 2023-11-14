@extends('layouts.admin.app')

@section('title', 'Tambah Transaksi')

@section('dataTransaksi', 'active')

@section('backlink')
    @if (auth()->user()->roles_id == 1)
        <a href="{{ route('admin.transaksi.index') }}"><i class="fa small pr-1 fa-arrow-left text-dark"></i></a>
    @endif
@endsection

@section('content')

    <!-- Tambah transaksi -->
    <div class="col-lg-12 col-lg-12 form-wrapper" id="tambah-transaksi">
        <div class="card">
            <div class="card-body">
                @if (auth()->user()->roles_id == 1)
                    <form method="POST" action="{{ route('admin.transaksi.store') }}" enctype="multipart/form-data">
                @endif
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Kode Pinjam</label>
                            <input type="text" class="form-control @error('kode_pinjam') is-invalid @enderror"
                                placeholder="kode_pinjam" name="kode_pinjam" id="kode_pinjam" value="{{ old('kode_pinjam') }}" required>
                            @error('kode_pinjam')
                                @if($message == "The kode pinjam has already been taken.")
                                    <div class="alert alert-danger">Kode pinjam sudah ada.</div>
                                @else
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Peminjam id</label>
                            <input type="number" class="form-control @error('peminjam_id') is-invalid @enderror"
                                placeholder="peminjam_id" name="peminjam_id" id="peminjam_id" value="{{ old('peminjam_id') }}" required>
                            @error('peminjam_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Petugas Pinjam</label>
                            <input type="text" class="form-control @error('petugas_pinjam') is-invalid @enderror"
                                placeholder="petugas_pinjam" name="petugas_pinjam" id="petugas_pinjam" value="{{ old('petugas_pinjam') }}" required>
                            @error('petugas_pinjam')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Petugas Kembali</label>
                            <input type="text" class="form-control @error('petugas_kembali') is-invalid @enderror"
                                placeholder="petugas_kembali" name="petugas_kembali" id="petugas_kembali" value="{{ old('petugas_kembali') }}" required>
                            @error('petugas_kembali')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" required>
                                <option value="Sedang Dipinjam" {{ old('status') == 'Sedang Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                                <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Terlambat" {{ old('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            </select>
                            @error('status')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                            placeholder="tanggal_pinjam" name="tanggal_pinjam" id="tanggal_pinjam"
                            value="{{ old('tanggal_pinjam') }}" required>
                        @error('tanggal_pinjam')
                            @if($message == "Tanggal tidak boleh lebih dari tanggal sekarang.")
                                <div class="alert alert-danger">{{ $message }}</div>
                            @else
                                <div class="alert alert-danger">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror"
                            placeholder="tanggal_kembali"
                            @if(isset($transaksi))
                                value="{{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('Y-m-d') }}"
                            @else
                                value="{{ old('tanggal_kembali') }}"
                            @endif
                            name="tanggal_kembali" id="tanggal_kembali" required>
                        @error('tanggal_kembali')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control @error('tanggal_pengembalian') is-invalid @enderror"
                            placeholder="tanggal_pengembalian"
                            @if(isset($transaksi))
                                value="{{ $transaksi->tanggal_pengembalian ? \Carbon\Carbon::parse($transaksi->tanggal_pengembalian)->format('Y-m-d') : '' }}"
                            @else
                                value="{{ old('tanggal_pengembalian') }}"
                            @endif
                            name="tanggal_pengembalian" id="tanggal_pengembalian">
                        @error('tanggal_pengembalian')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Denda</label>
                        <input type="number" class="form-control @error('denda') is-invalid @enderror"
                            placeholder="denda" value="{{ old('denda') }}" name="denda" id="denda">
                        @error('denda')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                  <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function hitungDenda() {
                var tanggalKembali = $('#tanggal_kembali').val();
                var tanggalPengembalian = $('#tanggal_pengembalian').val();

                // Periksa apakah kedua tanggal sudah diisi
                if (tanggalKembali && tanggalPengembalian) {
                    var selisihHari = Math.max(0, Math.ceil((new Date(tanggalPengembalian) - new Date(tanggalKembali)) / (1000 * 60 * 60 * 24)));
                    var biayaDendaPerHari = 1000;
                    var denda = selisihHari * biayaDendaPerHari;

                    $('#denda').val(denda);
                } else {
                    // Jika salah satu atau keduanya kosong, atur nilai denda menjadi 0
                    $('#denda').val(0);
                }
            }

            $('#tanggal_kembali, #tanggal_pengembalian, #denda').change(function () {
                // Hanya hitung denda jika tanggal kembali atau tanggal pengembalian diubah
                if ($(this).attr('id') !== 'denda') {
                    hitungDenda();
                }
            });

            hitungDenda(); // Panggil fungsi saat halaman dimuat
        });
    </script>

@endsection
