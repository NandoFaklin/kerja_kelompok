<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Anggota;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; 

class ApiKelompokController extends Controller
{
    //show kelompok
     public function showkelompok(Request $request){
        $kelompok = Kelompok::all();
        return response()->json([
            'status' => true,
            'message' => 'List Kelompok Succesfully',
            'data' => $kelompok
        ],200);
    }

    //show by nim_or_nip//
    public function showByNimOrNip(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nim_or_nip' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            // Cari semua anggota yang memiliki nim_or_nip yang cocok dengan input
            $anggota = Anggota::where('nim_or_nip_anggota', $request->nim_or_nip)->get();

            if ($anggota->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data anggota tidak ditemukan'
                ], 404);
            }

            // Array untuk menampung semua id_kelompok yang cocok
            $id_kelompok_list = [];

            // Loop through each anggota and collect id_kelompok
            foreach ($anggota as $anggota_item) {
                $id_kelompok_list[] = $anggota_item->id_kelompok;
            }

            // Query semua kelompok yang memiliki id_kelompok dari list yang sudah dikumpulkan
            $kelompok = Kelompok::whereIn('id_kelompok', $id_kelompok_list)->get();

            if ($kelompok->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kelompok tidak ditemukan untuk anggota ini'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $kelompok
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
    ///byid///
    public function showById(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_kelompok' => 'required|exists:kelompok,id_kelompok'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            // Ambil data kelompok berdasarkan id_kelompok
            $kelompok = Kelompok::find($request->id_kelompok);

            if (!$kelompok) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kelompok tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $kelompok
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
    ////show by id/////
    public function showAnggotaByKelompokbyid(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_kelompok' => 'required|exists:kelompok,id_kelompok'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 401);
            }

            // Cari semua anggota yang memiliki id_kelompok yang cocok dengan input
            $anggota = Anggota::where('id_kelompok', $request->id_kelompok)->get();

            if ($anggota->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data anggota tidak ditemukan untuk kelompok ini'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $anggota
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //create kelompok
    public function create(Request $request)
    {
    $validateKelompok = Validator::make($request->all(), [
        'nama_kelompok' => 'required',
        'mapel' => 'required',
        'user_id' => 'required',
    ]);

    if ($validateKelompok->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'data' => $validateKelompok->errors(),
        ], 422);
    }
    $namaKelompok = $request->input('nama_kelompok');
    $mapel = $request->input('mapel');
    $user_id = $request->input('user_id');

    $existingKelompok = Kelompok::where('nama_kelompok', $namaKelompok)
                                ->where('mapel', $mapel)
                                ->where('user_id', $user_id)
                                ->first();

    if ($existingKelompok) {
        return response()->json([
            'status' => false,
            'message' => 'The combination of nama_kelompok, mapel, and user_id already exists.',
        ], 422);
    }


    $inputData = array(
        'nama_kelompok' => $request->nama_kelompok,
        'mapel' => $request->mapel,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'user_id' => $request->user_id,
        'deskripsi' => isset($request->deskripsi) ? $request->deskripsi : '', // Gunakan optional() untuk mengisi deskripsi
    );

    $kelompok = Kelompok::create($inputData);

    return response()->json([
        'status' => true,
        'message' => 'Kelompok berhasil di tambahkan',
        'data' => $kelompok,
    ], 200);
}

//delete kelompok
public function delete(Request $request){
    // Validasi input request
    $validateKelompok = Validator::make($request->all(), [
        'id_kelompok' => 'required|exists:kelompok,id_kelompok',
    ]);

    if ($validateKelompok->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'data' => $validateKelompok->errors(),
        ], 422);
    }

    // Mulai transaksi database
    DB::beginTransaction();
    try {
        // Hapus data terkait di tabel user dan task
        Anggota::where('id_kelompok', $request->id_kelompok)->delete();
        Task::where('id_kelompok', $request->id_kelompok)->delete();
        
        // Hapus kelompok
        $kelompok = Kelompok::find($request->id_kelompok);
        if ($kelompok) {
            $kelompok->delete();
        }

        // Commit transaksi
        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Kelompok deleted successfully',
        ], 200);
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Failed to delete kelompok',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    //update nanma dan deskripsi
    public function update (Request $request){
        $validateKelompok = Validator::make($request->all(), [
            'id_kelompok' => 'required|exists:kelompok,id_kelompok',
            'deskripsi' => 'required',
        ]);
        if ($validateKelompok->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validateKelompok->errors(),
            ], 422);
        }

        $kelompok = Kelompok::find($request->id_kelompok);
        $kelompok->deskripsi = isset($request->deskripsi) ? $request->deskripsi : '';
        $kelompok->save();
        return response()->json([
            'status' => true,
            'message' => 'deskripsi berhasil di update',
            'data' => $kelompok,
        ], 200);

    }


    //add anggota
    public function add(Request $request)
    {
        $validateKelompok = Validator::make($request->all(), [
            'id_kelompok' => 'required|exists:kelompok,id_kelompok',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validateKelompok->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validateKelompok->errors(),
            ], 422);
        }

        // Periksa apakah kombinasi id_kelompok dan user_id sudah ada
        $exists = Kelompok::where('id_kelompok', $request->id_kelompok)
                        ->where('user_id', $request->user_id)
                        ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'User already exists in the group',
            ], 409);
        }

        //Tambahkan user_id baru ke kelompok
        $kelompok = Kelompok::find($request->id_kelompok);
        $currentUserIds = $kelompok->user_id ? explode(',', $kelompok->user_id) : [];

        if (!in_array($request->user_id, $currentUserIds)) {
            $currentUserIds[] = $request->user_id;
        }

        $kelompok->user_id = implode(',', $currentUserIds);
        $kelompok->save();


        return response()->json([
            'status' => true,
            'message' => 'User added successfully',
            'data' => $kelompok,
        ], 200);
    }


    
}