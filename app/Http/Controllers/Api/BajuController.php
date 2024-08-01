<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BajuResource;
use App\Models\Baju;
use App\Models\JenisBaju;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class BajuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $baju = Baju::with('jenisBaju')->get();
        
        return response()->json([ 
        'success' => true,
        'message' => 'List Data Baju',
        'data' => BajuResource::collection($baju)
    ] );
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
        try {
            $validator = Validator::make($request->all(), [
                'kode' => 'required|unique:baju,kode',
                'nama' => 'required',
                'id_jenis' => 'required',
                'harga' => 'required',
                'deskripsi' => 'required',
                'gambar' => 'required|image|mimes:jpeg,jpg,jfif,png|max:2048'
            ]);
        
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
        
            $image = $request->file('gambar');
            $imageName = $request->kode . date('YmdHis') . '.' . $image->getClientOriginalExtension();
            $image->storeAs('/image', $imageName);
        
             Baju::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'id_jenis' => $request->id_jenis,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'gambar' => $imageName,
            ]);
            $baju = Baju::with('jenisBaju')->findOrFail($request->kode);
            return response()->json([ 
                'success' => true,
                'message' => 'Data berhasil ditambahkan ',
                'data' => new BajuResource($baju)
            ] );
        }  catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan yang tidak terduga!',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $baju = Baju::with('jenisBaju')->findOrFail($id);
            return response()->json([ 
                'success' => true,
                'message' => 'Detail Data Baju! ',
                'data' => new BajuResource($baju)
            ] );
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
       try
        { $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            'id_jenis' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,jpg,jfif,png|max:2048'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $baju = Baju::with('jenisBaju')->findOrFail($id);
        if ($request->hasFile('gambar')){
            $image = $request->file('gambar');
            $imageName = $request->kode . date('YmdHis') . '.' . $image->getClientOriginalExtension();
            $image->storeAs('/image', $imageName);
                    if (file_exists(public_path('assets/image/' . basename($baju->gambar)))) {
                        Storage::disk('local')->delete('image/' . basename($baju->gambar));
                    } 
                    $baju->update([
                    'kode' => $request->kode,
                    'nama' => $request->nama,
                    'id_jenis' => $request->id_jenis,
                    'harga' => $request->harga,
                    'deskripsi' => $request->deskripsi,
                    'gambar' => $imageName,
                    ]);
            }else{
                $baju->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'id_jenis' => $request->id_jenis,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                ]);   
            }
            return response()->json([ 
                'success' => true,
                'message' => 'Data Berhasil diupdate ',
                'data' => new BajuResource(Baju::with('jenisBaju')->findOrFail($id))
            ] );
        }
            catch(\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menambahkan data jenis baju!',
                    'error' => $e->getMessage(),
                ], 500);
            }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
           
      try { $baju = Baju::with('jenisBaju')->findOrFail($id);
        Storage::disk('local')->delete('image/' . basename($baju->gambar));
        $baju->delete();
        return response()->json([ 
            'success' => true,
            'message' => 'Data berhasil dihapus ',
            'data' => new BajuResource($baju)
        ] );
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak tersedia!',
        ], 404);
    }
    }
}
