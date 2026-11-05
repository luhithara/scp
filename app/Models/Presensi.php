<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'siswaid',
        'jadwalid',
        'status',
        'keterangan',
        'waktu',
        'waktukeluar',
        'waktukembali',
        'alasankeluar',
        'diubaholeh',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswaid');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwalid');
    }

    public function pengubah()
    {
        return $this->belongsTo(User::class, 'diubaholeh');
    }

    public function sedangKeluar()
    {
        return $this->waktukeluar !== null
            && $this->waktukembali === null;
    }

    public function sudahKembali()
    {
        return $this->waktukeluar !== null
            && $this->waktukembali !== null;
    }
}