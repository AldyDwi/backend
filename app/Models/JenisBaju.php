<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBaju extends Model
{
    use HasFactory;

    protected $table = 'jenis_baju';
    protected $primarykey = 'id';
    protected $fillable = [
        'nama'
    ];

    public function baju()
    {
        return $this->hasMany(Baju::class, 'id_jenis');
    }


    
}
