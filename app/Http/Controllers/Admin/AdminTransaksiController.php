<?php

namespace App\Http\Controllers\Admin; // Sesuaikan dengan struktur direktori

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class AdminTransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Peminjaman::all();
        dd($transaksis->pluck('tanggal_pengembalian'));
        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function getPeminjamHampirJatuhTempo()
    {
        // Sesuaikan dengan logika bisnis Anda untuk mendapatkan data peminjam hampir jatuh tempo
        $peminjamHampirJatuhTempo = Peminjaman::where('tanggal_pengembalian', '<=', now()->addDays(3))
            ->where('status', '!=', 'selesai')
            ->get();

        return response()->json($peminjamHampirJatuhTempo);
    }

    public function create()
    {
        return view('admin.transaksi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pinjam' => 'required|max:255|unique:peminjaman,kode_pinjam',
            'peminjam_id' => 'required|max:255',
            'petugas_pinjam' => 'required|max:255',
            'petugas_kembali' => 'required|max:255',
            'status' => 'required|max:255',
            'denda' => 'nullable',
            'tanggal_pinjam' => [
                'required',
                'max:255',
                'date',
                function ($attribute, $value, $fail) {
                    $tanggalInput = strtotime($value);
                    $tanggalSekarang = strtotime(now()->toDateString());

                    if ($tanggalInput > $tanggalSekarang) {
                        $fail("Tanggal tidak boleh lebih dari tanggal sekarang.");
                    }
                },
            ],
            'tanggal_kembali' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
                'max:255',
            ],
            'tanggal_pengembalian' => 'nullable|date|max:255',
        ]);

        $request['tanggal_pengembalian'] = $request->has('tanggal_pengembalian') ? $request->tanggal_pengembalian : null;

        dd($request->all());

        Peminjaman::create($request->all());

        return redirect()->route('admin.transaksi.index')->with('sukses', 'Berhasil Tambah Data!');
    }

public function update(Request $request, $id)
{
    $request->validate([
        'kode_pinjam' => 'required|max:255',
        'peminjam_id' => 'required|max:255',
        'petugas_pinjam' => 'required|max:255',
        'petugas_kembali' => 'required|max:255',
        'status' => 'required|max:255',
        'denda' => 'nullable',
        'tanggal_pinjam' => [
            'required',
            'max:255',
            'date',
            function ($attribute, $value, $fail) {
                $tanggalInput = strtotime($value);
                $tanggalSekarang = strtotime(now()->toDateString());

                if ($tanggalInput > $tanggalSekarang) {
                    $fail("Tanggal tidak boleh lebih dari tanggal sekarang.");
                }
            },
        ],
        'tanggal_kembali' => [
            'required',
            'date',
            'after_or_equal:tanggal_pinjam',
            'max:255',
        ],
        'tanggal_pengembalian' => 'nullable|date|max:255',
    ]);

    $request['tanggal_pengembalian'] = $request->has('tanggal_pengembalian') ? $request->tanggal_pengembalian : null;

    $transaksi = Peminjaman::findOrFail($id);
    $transaksi->update($request->all());

    return redirect()->route('admin.transaksi.index')->with('sukses', 'Berhasil Edit Data!');
}

    public function show($id)
    {
        $transaksi = Peminjaman::findOrFail($id);
        return view('admin.transaksi.read', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = Peminjaman::findOrFail($id);
        return view('admin.transaksi.update', compact('transaksi'));
    }

    public function destroy($id)
    {
        $transaksi = Peminjaman::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('admin.transaksi.index')->with('sukses', 'Berhasil Hapus Data!');
    }
}
