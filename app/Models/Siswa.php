<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'jeniskelamin',
        'tanggallahir',
        'tempatlahir',
        'alamat',
        'nomortelepon',
        'kelasid',
    ];

    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'kelasid'
        );
    }

    public function izin()
    {
        return $this->hasMany(
            IzinSiswa::class,
            'siswaid'
        );
    }
}