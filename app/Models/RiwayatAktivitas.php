<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatAktivitas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_aktivitas';

    protected $fillable = [
        'userid',
        'aktivitas',
        'tabel',
        'dataid',
        'datasebelum',
        'datasesudah',
        'ipaddress',
    ];

    protected $casts = [
        'datasebelum' => 'array',
        'datasesudah' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}