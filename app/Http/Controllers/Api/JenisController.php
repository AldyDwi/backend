<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JenisResource;
use App\Models\JenisBaju;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisBajuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisBaju = JenisBaju::all();
        return new JenisResource(true, 'List Data Jenis Baju',$jenisBaju);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jenis= JenisBaju::create([
            'nama' => $request->nama,
        ]);
        return new JenisResource(true, 'Data Jenis Baju Berhasil Ditambahkan!',$jenis);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $jenisBaju = JenisBaju::findOrFail($id);
            return new JenisResource(true, 'Detail Data Jenis Baju!', $jenisBaju);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia!',
            ], 404);
        }
            }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $jenisBaju = JenisBaju::findorfail($id);
        $jenisBaju->update([
            'nama' => $request->nama,
        ]);
        return new JenisResource(true, 'Data Post Berhasil Diubah!',$jenisBaju);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $jenisBaju = JenisBaju::findorfail($id);
        $jenisBaju->delete();
        return new JenisResource(true, 'Data Post Berhasil Dihapus!',$jenisBaju);

    }
}
