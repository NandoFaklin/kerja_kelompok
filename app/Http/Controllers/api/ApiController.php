<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;


class ApiController extends Controller
{
    //register
    public function register(Request $request){
        try{
        $validateUser = Validator::make($request->all(),
    [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required',
        'role' => 'required',
        'nim_or_nip' => 'required|unique:users,nim_or_nip',  ]
    );
    if($validateUser->fails()){
        return response()->json([
            'status'=> false,
            'message' => 'validation error',
            'errors' => $validateUser->errors()
        ],401);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'role' => $request->role,
        'nim_or_nip' => $request->nim_or_nip

    ]);
    return response()->json([
        'status'=> true,
        'message' => 'Register sukses',
        'token' => $user->createToken("API TOKEN")->plainTextToken,
        'data' => $user
    ],200);
}catch (\Throwable $th){
    return response()->json([
        'status'=> false,
        'message' => $th->getMessage(),500]);
}
   
    }


    //login
    public function login(Request $request){
        try{
            
        $validateUser = Validator::make($request->all(),
        [
           
            'nim_or_nip' => 'required|exists:users,nim_or_nip',
            'password' => 'required'
        ]
        );
        if($validateUser->fails()){
            return response()->json([
                'status'=> false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ],401);
        }
        if(!Auth::attempt($request->only(['nim_or_nip','password']))){
            
            return response()->json([
                'status'=> false,
                'message' => 'nim_or_nip& password salah',
            ],401);
        }
        $user = User::where('nim_or_nip',$request->nim_or_nip)->first();
        return response()->json([
            'status'=> true,
            'message' => 'Login sukses',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'data' => $user

        ],200);

        }catch (\Throwable $th){
            return response()->json([
                'status'=> false,
                'message' => $th->getMessage(),500]);
        }
}

//profile 

public function Profile(){
    $userData = auth()->user();
    return response()->json([
        'status'=> true,
        'message' => 'Profile Informasi',
        'data' => $userData,
        'id' => auth()->user()->id
    ],200);
}

//logout
public function Logout(){
    auth()->user()->tokens()->delete();
    return response()->json([
        'status'=> true,
        'message' => 'User logged out',
        'data' => [],
    ],200);
}

//emailcheck
public function checkEmail(Request $request)
{
    try {
        $email = $request->input('email');

        if ($user = User::where('email', $email)->first()) {
            // Generate API token
            $token = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Email verified',
                'data' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email not found',
            ], 404);
        }
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}



public function showUserByNimOrNip(Request $request)
{
    try {
        $nim_or_nip = $request->input('nim_or_nip');
        if ($user = User::where('nim_or_nip', $nim_or_nip)->first()) {
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditemukan',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data user tidak ditemukan'
            ], 404);
        }
    } catch (Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}

public function adminLogin(Request $request)
{
    try {
        // Validasi input
        $validateAdmin = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validateAdmin->fails()) {
            return redirect('/login')
                ->withErrors($validateAdmin)
                ->withInput();
        }

        // Cek apakah kredensial cocok
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/login')
                ->withErrors(['password' => 'Email atau password salah'])
                ->withInput();
        }

        // Cek apakah user adalah admin
        $user = User::where('email', $request->email)->first();
        if ($user->role !== 'Admin') {
            return redirect('/login')
                ->withErrors(['role' => 'Akses ditolak. Hanya admin yang bisa login.'])
                ->withInput();
        }

        // Simpan informasi user di session
        session(['user' => $user]);

        // Redirect ke halaman home
        return redirect('/home');

    } catch (\Throwable $th) {
        return redirect('/login')
            ->withErrors(['error' => $th->getMessage()])
            ->withInput();
    }
}


public function showuser()
{
    try {
        // Mengambil semua data pengguna kecuali yang memiliki role 'Admin'
        $users = User::where('role', '!=', 'Admin')->get();

        // Mengembalikan data dalam bentuk JSON
        return response()->json([
            'status' => true,
            'message' => 'Daftar semua pengguna kecuali admin berhasil diambil',
            'data' => $users
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}

public function deleteUser(Request $request)
{
    try {
        // Ambil ID pengguna dari parameter request
        $id = $request->input('id');

        // Cari user berdasarkan id
        $user = User::find($id);

        // Jika user tidak ditemukan, kembalikan respons 404
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Hapus user
        $user->delete();

        // Kembalikan respons sukses
        return response()->json([
            'status' => true,
            'message' => 'User berhasil dihapus'
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}


public function validasi(Request $request)
{
    try {
        $id = $request->input('id');

        // Temukan data berdasarkan ID
        $validasiUser = Admin::find($id);

        if (!$validasiUser) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan di validasi_user'
            ], 404);
        }

        // Hapus data dari tabel validasi_user
        $validasiUser->delete();

        // Cek data yang diambil
        Log::info('Data ValidasiUser:', $validasiUser->toArray());

        // Hash password sebelum disimpan
        $hashedPassword = Hash::make($validasiUser->password);

        // Validasi data untuk tabel users
        $validateUser = Validator::make([
            'name' => $validasiUser->name,
            'email' => $validasiUser->email,
            'password' => $validasiUser->password,
            'role' => $validasiUser->role,
            'nim_or_nip' => $validasiUser->nim_or_nip
        ], [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required|in:Mahasiswa,Dosen',
            'nim_or_nip' => 'required|unique:users,nim_or_nip'
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        // Buat user baru di tabel users
        $user = User::create([
            'name' => $validasiUser->name,
            'email' => $validasiUser->email,
            'password' => $hashedPassword,
            'role' => $validasiUser->role,
            'nim_or_nip' => $validasiUser->nim_or_nip
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dipindahkan dan dihapus dari validasi_user',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'data' => $user
        ], 200);

    } catch (Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}








}
