<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baju extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'id_jenis', 'deskripsi', 'harga', 'gambar'];

    protected $table = 'baju';

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'id_jenis');
    }
}
