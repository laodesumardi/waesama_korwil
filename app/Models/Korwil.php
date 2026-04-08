<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Korwil extends Model
{
    use HasFactory;

    protected $table = 'korwil';

    protected $fillable = [
        'user_id',
        'kode_wilayah',
        'nama_korwil',
        'wilayah_kerja',
        'no_sk',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Sekolah
    public function sekolah()
    {
        return $this->hasMany(Sekolah::class, 'id_korwil');
    }

    // Relasi ke UserAssignment (polymorphic)
    public function assignments()
    {
        return $this->morphMany(UserAssignment::class, 'target');
    }

    // Helper untuk mendapatkan jumlah sekolah
    public function getJumlahSekolahAttribute()
    {
        return $this->sekolah()->count();
    }

    // Helper untuk mendapatkan status user
    public function getUserStatusAttribute()
    {
        return $this->user ? ($this->user->is_active ? 'Aktif' : 'Nonaktif') : '-';
    }
}
