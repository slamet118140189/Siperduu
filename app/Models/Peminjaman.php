<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $fillable = [
        'kode_pinjam',
        'peminjam_id',
        'petugas_pinjam',
        'petugas_kembali',
        'status',
        'denda',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_pengembalian',
    ];

    // relation
    public function detail_peminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // accessor
    public function getDendaAttribute($value)
    {
        return $value !== null ? $value : 0;
    }

    public function getTanggalPinjamAttribute($value)
    {
        return Carbon::create($value)->format('d-M-Y');
    }

    public function getTanggalKembaliAttribute($value)
    {
        return Carbon::create($value)->format('d-M-Y');
    }

    public function getTanggalPengembalianAttribute($value)
    {
        return Carbon::create($value)->format('d-M-Y');
    }

    // event
    protected static function booted()
    {
        static::saving(function ($peminjaman) {
            // Hitung selisih hari antara tanggal kembali dan tanggal pengembalian
            $selisihHari = Carbon::parse($peminjaman->tanggal_pengembalian)->diffInDays(Carbon::parse($peminjaman->tanggal_kembali));

            // Hitung denda (misalnya, 1000 per hari)
            $denda = $selisihHari * 1000;

            // Set nilai denda pada model
            $peminjaman->denda = $denda;
        });
    }

    public function hitungDenda()
    {
        // Tambahkan logika untuk menghitung denda berdasarkan selisih tanggal
        $tanggalKembali = Carbon::parse($this->tanggal_kembali);
        $tanggalPengembalian = Carbon::parse($this->tanggal_pengembalian);

        // Hitung selisih hari
        $selisihHari = $tanggalKembali->diffInDays($tanggalPengembalian, false);

        // Hitung denda (misalnya, biaya denda per hari adalah 1000)
        $biayaDendaPerHari = 500;
        $denda = max(0, $selisihHari) * $biayaDendaPerHari;

        // Atur nilai denda pada model
        $this->denda = $denda;
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'Selesai':
                return 'green'; // warna hijau untuk status selesai
                break;
            case 'Sedang Dipinjam':
                return 'yellow'; // warna kuning untuk status berlangsung
                break;
            case 'Terlambat':
                return 'red'; // warna merah untuk status terlambat
                break;
            default:
                return ''; // warna default jika status tidak sesuai
        }
    }

    // Validasi
    public static function rules($id = null)
    {
        return [
            'kode_pinjam' => [
                'required',
                'max:255',
                Rule::unique('peminjaman')->ignore($id),
            ],
            'peminjam_id' => 'required|numeric',
            'petugas_pinjam' => 'required|max:255',
            'petugas_kembali' => 'required|max:255',
            'status' => 'required|max:255',
            'denda' => 'nullable|numeric',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'tanggal_pengembalian' => 'nullable|date',
        ];
    }
}
