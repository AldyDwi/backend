<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Baju extends Model
{
    use HasFactory;
    protected $table = 'baju';
    protected $primaryKey = 'kode';
    public $incrementing = false; // if kode is not an auto-incrementing field
    protected $fillable = [
        'kode',
        'nama',
        'id_jenis', 
        'deskripsi', 
        'harga', 
        'gambar'

    ];


    public function jenisBaju()
    {
        return $this->belongsTo(JenisBaju::class, 'id_jenis');
    }
    protected function gambar(): Attribute
    {
        return Attribute::make(
            get: fn ($gambar) => asset('/assets/image/' . $gambar),
        );
    }

}
