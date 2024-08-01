<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BajuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        return  [
            
                'kode' => $this->kode,
                'nama' => $this->nama,
                'id_jenis' => $this->id_jenis,
                'nama_jenis' => $this->whenLoaded('jenisBaju', function () {
                    return $this->jenisBaju->nama;
                }),
                'harga' => $this->harga,
                'deskripsi' => $this->deskripsi,
                //  'gambar' => asset('assets/images/' . $this->gambar),
                'gambar' => $this->gambar,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                
            
        ];
    }
}
