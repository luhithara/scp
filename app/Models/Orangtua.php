<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orangtua extends Model
{
    protected $table = 'orangtuas';

    protected $fillable = [
        'siswaid',
        'nama',
        'hubungan',
        'nomortelepon',
        'alamat',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswaid');
    }
}