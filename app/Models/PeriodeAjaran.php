<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeAjaran extends Model
{
    use HasFactory;

    protected $table = 'periode_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi ke Absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_periode');
    }

    // Scope untuk periode aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper untuk mendapatkan label semester
    public function getSemesterLabelAttribute()
    {
        return $this->semester == 1 ? 'Ganjil' : 'Genap';
    }

    // Helper untuk mendapatkan label lengkap
    public function getLabelAttribute()
    {
        return $this->tahun_ajaran . ' - Semester ' . $this->semester_label;
    }

    // Helper untuk cek apakah periode sedang berlangsung
    public function isCurrent()
    {
        $now = now();
        return $this->tanggal_mulai <= $now && $this->tanggal_selesai >= $now;
    }

    // Helper untuk mendapatkan status periode
    public function getStatusPeriodeAttribute()
    {
        if ($this->is_active && $this->isCurrent()) {
            return 'Berjalan';
        } elseif ($this->is_active && !$this->isCurrent()) {
            return 'Aktif (Belum Mulai)';
        } elseif (!$this->is_active && $this->tanggal_selesai < now()) {
            return 'Selesai';
        } else {
            return 'Tidak Aktif';
        }
    }

    // Boot method untuk memastikan hanya satu periode aktif
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($periode) {
            if ($periode->is_active) {
                static::where('is_active', true)
                    ->where('id', '!=', $periode->id)
                    ->update(['is_active' => false]);
            }
        });
    }
}
