<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BK extends Model
{
    use HasFactory;

    protected $table = 'bk';

    protected $fillable = [
        'userid',
        'kodebk',
        'nama',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function kelas()
    {
        return $this->belongsToMany(
            Kelas::class,
            'bk_kelas',
            'bkid',
            'kelasid'
        )->withTimestamps();
    }
}