<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ApiControllerAdmin extends Controller
{
    public function registerValidasi(Request $request)
    {
        try {
            // Validasi input
            $validateUser = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    function($attribute, $value, $fail) {
                        // Validasi nama di tabel users
                        if (User::where('name', $value)->exists()) {
                            $fail('Nama sudah digunakan di tabel users.');
                        }
                    },
                    'unique:validasi_user,name'
                ],
                'email' => [
                    'required',
                    'email',
                    function($attribute, $value, $fail) {
                        // Validasi email di tabel users
                        if (User::where('email', $value)->exists()) {
                            $fail('Email sudah digunakan di tabel users.');
                        }
                    },
                    'unique:validasi_user,email'
                ],
                'password' => 'required',
                'role' => 'required|in:Mahasiswa,Dosen', // Validasi role harus Mahasiswa atau Dosen
                'nim_or_nip' => [
                    'required',
                    function($attribute, $value, $fail) {
                        // Validasi NIM/NIP di tabel users
                        if (User::where('nim_or_nip', $value)->exists()) {
                            $fail('NIM/NIP sudah digunakan di tabel users.');
                        }
                    },
                    'unique:validasi_user,nim_or_nip'
                ]
            ]);
    
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
    
            // Hash password sebelum disimpan
            $hashedPassword = Hash::make($request->password);
    
            // Buat user baru di tabel validasi_user
            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPassword,
                'role' => $request->role,
                'nim_or_nip' => $request->nim_or_nip
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Register sukses',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'data' => $user
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getAllValidasiUsers()
    {
        try {
            // Ambil semua data dari tabel validasi_user
            $users = Admin::all();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diambil',
                'data' => $users
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function deleteValidasi(Request $request)
{
    try {
        // Ambil ID pengguna dari parameter request
        $id = $request->input('id');

        // Cari user berdasarkan id
        $user = Admin::find($id);

        // Jika user tidak ditemukan, kembalikan respons 404
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'data validasi tidak ditemukan'
            ], 404);
        }

        // Hapus user
        $user->delete();

        // Kembalikan respons sukses
        return response()->json([
            'status' => true,
            'message' => 'Validasi berhasil dihapus'
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}

    
}
