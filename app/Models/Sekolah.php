<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';

    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'alamat',
        'kelurahan',
        'kecamatan',
        'id_korwil',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relasi ke Korwil
    public function korwil()
    {
        return $this->belongsTo(Korwil::class, 'id_korwil');
    }

    // Relasi ke UserAssignment (polymorphic)
    public function assignments()
    {
        return $this->morphMany(UserAssignment::class, 'target', 'target_type', 'target_id');
    }

    // Relasi ke Absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_sekolah');
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah');
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->hasMany(Guru::class, 'id_sekolah');
    }

    // Helper untuk cek status
    public function isAktif()
    {
        return $this->status === 'aktif';
    }

    // Helper untuk get nama korwil
    public function getNamaKorwilAttribute()
    {
        return $this->korwil ? $this->korwil->nama_korwil : '-';
    }

    // Scope untuk filter
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }
}
