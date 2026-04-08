<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'id_sekolah',
        'id_periode',
        'jenis_absensi',
        'tanggal',
        'jumlah_hadir',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_alpha',
        'total_siswa',
        'keterangan',
        'detail_absensi',
        'foto',
        'diinput_oleh',
        'status_validasi',
        'divalidasi_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_hadir' => 'integer',
        'jumlah_sakit' => 'integer',
        'jumlah_izin' => 'integer',
        'jumlah_alpha' => 'integer',
        'total_siswa' => 'integer',
        'detail_absensi' => 'array',
    ];

    // Auto calculate total_siswa
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($absensi) {
            $absensi->total_siswa = $absensi->jumlah_hadir + $absensi->jumlah_sakit + $absensi->jumlah_izin + $absensi->jumlah_alpha;
        });
    }

    // Relasi
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeAjaran::class, 'id_periode');
    }

    public function inputer()
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status_validasi) {
            'disetujui' => '<span class="badge bg-success-soft px-3 py-1 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Disetujui</span>',
            'ditolak' => '<span class="badge bg-danger-soft px-3 py-1 rounded-pill"><i class="bi bi-x-circle-fill me-1"></i> Ditolak</span>',
            default => '<span class="badge bg-warning-soft px-3 py-1 rounded-pill"><i class="bi bi-clock-history me-1"></i> Pending</span>'
        };
    }
}
