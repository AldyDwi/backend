<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\JenisResource;
use App\Models\JenisBaju;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\HttpResponses;


class JenisBajuController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisBaju = JenisBaju::all();
        return $this->success(JenisResource::collection($jenisBaju),'List Data Jenis Baju');

        // return new JenisResource(true, 'List Data Jenis Baju',$jenisBaju);
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

        $id = JenisBaju::max('id') + 1;

        $jenis= JenisBaju::create([
            'id' => $id,
            'nama' => $request->nama,
        ]);
        return $this->success(new JenisResource($jenis),'Data Jenis Baju Berhasil Ditambahkan!');
        // return new JenisResource($jenis);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $jenisBaju = JenisBaju::findOrFail($id);
            return $this->success(new JenisResource($jenisBaju),'List Data Jenis Baju');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Data tidak tersedia!', 404);
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
        return $this->success(new JenisResource($jenisBaju),'Data berhasil diperbarui!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $jenisBaju = JenisBaju::findorfail($id);
        $jenisBaju->delete();
        return $this->success(new JenisResource($jenisBaju),'Data berhasil terhapus!');


    }
}
