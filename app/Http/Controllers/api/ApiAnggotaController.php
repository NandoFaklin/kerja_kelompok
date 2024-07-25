<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use Illuminate\Support\Facades\Validator;

class ApiAnggotaController extends Controller
{

//show by id_kelompok
public function showById(Request $request)
{
    try {
        $validateKelompok = Validator::make($request->all(), [
            'id_kelompok' => 'required|exists:kelompok,id_kelompok'
        ]);

        if ($validateKelompok->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateKelompok->errors()
            ], 401);
        }

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




//add anggota
public function add(Request $request)
{
    try {
        $validateAnggota = Validator::make($request->all(), [
            'id_kelompok' => 'required|exists:kelompok,id_kelompok', // Menambahkan validasi bahwa id_kelompok harus ada di tabel kelompok
            'nama_anggota' => 'required',
            'nim_or_nip_anggota' => 'required',
            'role' => 'required',
            // Tambahkan validasi atau kolom lain yang diperlukan
        ]);

        if ($validateAnggota->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateAnggota->errors()
            ], 401);
        }

        // Cek apakah kombinasi id_kelompok dan nim_or_nip_anggota sudah ada
        $existingAnggota = Anggota::where('id_kelompok', $request->id_kelompok)
                                  ->where('nim_or_nip_anggota', $request->nim_or_nip_anggota)
                                  ->first();

        if ($existingAnggota) {
            return response()->json([
                'status' => false,
                'message' => 'Anggota sudah terdaftar.'
            ], 422);
        }

        Anggota::create([
            'id_kelompok' => $request->id_kelompok,
            'nama_anggota' => $request->nama_anggota,
            'nim_or_nip_anggota' => $request->nim_or_nip_anggota,
            'role' => $request->role,  
            // Tambah kolom lain yang diperlukan
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Anggota berhasil ditambahkan'
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}

    

    //delete anggota
    public function delete(Request $request)
{
    try {
        $validateAnggota = Validator::make($request->all(), [
            'id_anggota' => 'required|exists:anggota,id_anggota'
        ]);

        if ($validateAnggota->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateAnggota->errors()
            ], 401);
        }

        $anggota = Anggota::find($request->id_anggota);

        if (!$anggota) {
            return response()->json([
                'status' => false,
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        $anggota->delete();

        return response()->json([
            'status' => true,
            'message' => 'Anggota berhasil dihapus'
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}


}
