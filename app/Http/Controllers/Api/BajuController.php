<?php

namespace App\Http\Controllers\Api;

use App\Models\Baju;
use App\Models\Jenis;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class BajuController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $baju = Baju::with('jenis')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'jenis' => $item->jenis->nama, 
                    'deskripsi' => $item->deskripsi,
                    'harga' => $item->harga,
                    'gambar' => $item->gambar,
                ];
            });
            return $this->success($baju, 'Data baju berhasil diambil');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'id_jenis' => 'required|exists:jenis,id',
                'deskripsi' => 'required|string',
                'harga' => 'required|numeric',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($validate->fails()) {
                return $this->error('Validation error', 401, $validate->errors());
            }

            $data = $request->all();

            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('public/gambar');
                $data['gambar'] = basename($path);
            }

            $baju = Baju::create($data);
            
            return $this->success($baju, 'Data baju berhasil ditambahkan');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $baju = Baju::with('jenis')->findOrFail($id);
            
            return $this->success([
                'id' => $baju->id,
                'nama' => $baju->nama,
                'jenis' => $baju->jenis->nama, 
                'deskripsi' => $baju->deskripsi,
                'harga' => $baju->harga,
                'gambar' => $baju->gambar,
            ], 'Data baju ditemukan');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'id_jenis' => 'required|exists:jenis,id',
                'deskripsi' => 'required|string',
                'harga' => 'required|numeric',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($validate->fails()) {
                return $this->error('Validation error', 401, $validate->errors());
            }

            $baju = Baju::findOrFail($id);
            $data = $request->all();

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($baju->gambar) {
                    Storage::delete('public/gambar/' . $baju->gambar);
                }
                $path = $request->file('gambar')->store('public/gambar');
                $data['gambar'] = basename($path);
            }

            $baju->update($data);
            
            return $this->success($baju, 'Data baju berhasil diupdate');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $baju = Baju::findOrFail($id);

            // Hapus gambar jika ada
            if ($baju->gambar) {
                Storage::delete('public/gambar/' . $baju->gambar);
            }

            $baju->delete();
            
            return $this->success([], 'Data baju berhasil dihapus');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
