<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'guruid',
        'mapelid',
        'namamapel',
        'tingkat',
        'jurusanid',
        'putaranblok',
        'kelasid',
        'ruanganid',
        'hari',
        'jamke',
        'jamMulai',
        'jamSelesai',
        'blok',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guruid');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapelid');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelasid');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruanganid');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'jadwalid');
    }

    public function jurnal()
    {
        return $this->hasMany(JurnalMengajar::class, 'jadwalid');
    }

    public function izin()
    {
        return $this->hasMany(IzinSiswa::class, 'jadwalid');
    }
}