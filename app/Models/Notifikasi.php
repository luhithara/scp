<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'userid',
        'judul',
        'pesan',
        'jenis',
        'url',
        'sudahdibaca',
        'waktudibaca',
    ];

    protected $casts = [
        'sudahdibaca' => 'boolean',
        'waktudibaca' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}