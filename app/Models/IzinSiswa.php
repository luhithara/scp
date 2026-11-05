<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinSiswa extends Model
{
    use HasFactory;

    protected $table = 'izin_siswa';

    protected $fillable = [
        'siswaid',
        'jenis',
        'alasan',
        'bukti',
        'tanggal',
        'waktu',
        'status',
        'jadwalid',
        'disetujui_oleh',
        'waktudisetuju',
        'catatanbk',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime',
        'waktudisetuju' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class,
            'siswaid'
        );
    }

    public function jadwal()
    {
        return $this->belongsTo(
            Jadwal::class,
            'jadwalid'
        );
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(
            User::class,
            'disetujui_oleh'
        );
    }
}