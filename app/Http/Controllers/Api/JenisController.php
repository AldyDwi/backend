<?php

namespace App\Http\Controllers\Api;

use App\Models\Jenis;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class JenisController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $jenis = Jenis::all();
            
            return $this->success($jenis, 'Data jenis berhasil diambil');
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
            ]);

            if ($validate->fails()) {
                return $this->error('Validation error', 401, $validate->errors());
            }

            $jenis = Jenis::create($request->all());
            
            return $this->success($jenis, 'Data jenis berhasil ditambahkan');
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
            $jenis = Jenis::findOrFail($id);
            
            return $this->success($jenis, 'Data jenis ditemukan');
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
            ]);

            if ($validate->fails()) {
                return $this->error('Validation error', 401, $validate->errors());
            }

            $jenis = Jenis::findOrFail($id);
            $jenis->update($request->all());
            
            return $this->success($jenis, 'Data jenis berhasil diupdate');
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
            $jenis = Jenis::findOrFail($id);
            $jenis->delete();

            return $this->success([], 'Data jenis berhasil dihapus');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
