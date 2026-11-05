<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuruMapel extends Model
{
    protected $table = 'guru_mapel';

    protected $fillable = [
        'guruid',
        'mapelid',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guruid');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapelid');
    }
}