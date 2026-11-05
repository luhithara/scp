<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'namakelas',
        'jurusanid',
        'tingkat',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusanid');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelasid');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kelasid');
    }

    public function bk()
    {
        return $this->belongsToMany(
            BK::class,
            'bk_kelas',
            'kelasid',
            'bkid'
        )->withTimestamps();
    }

    public function waliKelas()
    {
        return $this->hasOne(WaliKelas::class, 'kelasid');
    }
}