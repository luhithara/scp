<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinBukti extends Model
{
    use HasFactory;

    protected $table = 'izin_bukti';

    protected $fillable = [
        'izinid',
        'jenisfile',
        'file',
    ];

    public function izin()
    {
        return $this->belongsTo(IzinSiswa::class, 'izinid');
    }
}