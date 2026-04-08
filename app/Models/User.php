<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'foto',
        'role',
        'created_by',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // Relasi: User yang membuat record ini
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi: User yang dibuat oleh user ini
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    // Relasi ke Korwil
    public function korwil()
    {
        return $this->hasOne(Korwil::class, 'user_id');
    }

    // Relasi ke UserAssignment
    public function assignments()
    {
        return $this->hasMany(UserAssignment::class, 'user_id');
    }

    // Relasi ke Absensi sebagai inputer
    public function absensiInput()
    {
        return $this->hasMany(Absensi::class, 'diinput_oleh');
    }

    // Relasi ke Absensi sebagai validator
    public function absensiValidasi()
    {
        return $this->hasMany(Absensi::class, 'divalidasi_oleh');
    }

    // Helper untuk cek role
    public function isAdmin()
    {
        return $this->role === 'admin_dinas';
    }

    public function isOperator()
    {
        return $this->role === 'operator_sekolah';
    }

    public function isKorwil()
    {
        return $this->role === 'korwil';
    }

    // Helper untuk get assignment
    public function getAssignedSekolah()
    {
        $assignment = $this->assignments()->where('target_type', 'sekolah')->first();
        if ($assignment) {
            return Sekolah::find($assignment->target_id);
        }
        return null;
    }

    public function getAssignedKorwil()
    {
        $assignment = $this->assignments()->where('target_type', 'korwil')->first();
        if ($assignment) {
            return Korwil::find($assignment->target_id);
        }
        return null;
    }
}
