<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jabatan extends Model
{
    protected $table = 'jabatan';

    protected $fillable = [
        'namajabatan',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'userjabatan',
            'jabatanid',
            'userid'
        );
    }
}