<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    use HasFactory;

    protected $table = 'user_assignments';

    protected $fillable = [
        'user_id',
        'target_type',
        'target_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Polymorphic relation - tanpa morph map
    public function target()
    {
        return $this->morphTo();
    }
}
