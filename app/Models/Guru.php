<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'kodeguru',
        'nama',
        'nip',
        'nomortelepon',
        'alamat',
    ];

    /*
    |--------------------------------------------------------------------------
    | JADWAL MENGAJAR
    |--------------------------------------------------------------------------
    */

    public function jadwal()
    {
        return $this->hasMany(
            Jadwal::class,
            'guruid'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | JURNAL MENGAJAR
    |--------------------------------------------------------------------------
    */

    public function jurnal()
    {
        return $this->hasMany(
            JurnalMengajar::class,
            'guruid'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MATA PELAJARAN YANG DIAMPU
    |--------------------------------------------------------------------------
    */

    public function mapel()
    {
        return $this->belongsToMany(
            Mapel::class,
            'guru_mapel',
            'guruid',
            'mapelid'
        )->withTimestamps();
    }
}