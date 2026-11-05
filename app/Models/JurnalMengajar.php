<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalMengajar extends Model
{
    use HasFactory;

    protected $table = 'jurnal_mengajar';

    protected $fillable = [
        'jadwalid',
        'guruid',
        'tanggal',
        'materi',
        'kegiatan',
        'catatan',
        'fotobukti',
        'diubaholeh',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwalid');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guruid');
    }

    public function pengubah()
    {
        return $this->belongsTo(User::class, 'diubaholeh');
    }
}