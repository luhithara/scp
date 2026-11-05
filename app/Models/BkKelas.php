<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkKelas extends Model
{
    use HasFactory;

    protected $table = 'bk_kelas';

    protected $fillable = [
        'bkid',
        'kelasid',
    ];

    public function bk()
    {
        return $this->belongsTo(BK::class, 'bkid');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelasid');
    }
}