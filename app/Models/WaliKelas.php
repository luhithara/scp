<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    use HasFactory;

    protected $table = 'wali_kelas';

    protected $fillable = [
        'kelasid',
        'userid',
        'kodewalikelas',
        'namaakun',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelasid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}