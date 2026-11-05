<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waka extends Model
{
    use HasFactory;

    protected $table = 'waka';

    protected $fillable = [
        'userid',
        'kodewaka',
        'nama',
        'nip',
        'nomortelepon',
        'alamat',
        'jabatan',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'userid'
        );
    }
}