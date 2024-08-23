<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\BajuResource;
use App\Models\Baju;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Traits\HttpResponses;


class BajuController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $baju = Baju::with('jenisBaju')->get();
        return $this->success(BajuResource::collection($baju),'List Data Jenis Baju');



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
            return $this->success(new BajuResource($baju),'Data Jenis Baju Berhasil Ditambahkan!');

        }  catch (\Exception $e) {
            return $this->error('Terjadi kesalahan yang tidak terduga!', 500);

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
            return $this->success(new BajuResource($baju),'List Data Jenis Baju');

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
            return $this->success(new BajuResource($baju),'Data berhasil diperbarui!');

        }
            catch(\Exception $e) {
                return $this->error('Terjadi kesalahan yang tidak terduga!', 500);
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
        return $this->success(new BajuResource($baju),'Data berhasil terhapus!');

        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return $this->error('Data tidak tersedia!', 404);

    }
    }
}
