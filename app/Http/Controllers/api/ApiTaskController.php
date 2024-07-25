<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;

class ApiTaskController extends Controller
{
    //show by nim_or_nip_task///
public function showByNipOrNipTask(Request $request)
{
    try {
        $validateTask = Validator::make($request->all(), [
            'nim_or_nip_task' => 'required'
        ]);

        if ($validateTask->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateTask->errors()
            ], 401);
        }

        $tasks = Task::where('nim_or_nip_task', $request->nim_or_nip_task)->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data task tidak ditemukan untuk nim atau nip task ini'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $tasks
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}

    /// add task///
    public function add(Request $request)
    {
        try {
            $validateTask = Validator::make($request->all(), [
                'nama_task' => 'required',
                'nama_user' => 'required',
                'nim_or_nip_task' => 'required',
                'id_kelompok' => 'required|exists:kelompok,id_kelompok'
                // Tambahkan validasi atau kolom lain yang diperlukan
            ]);
    
            if ($validateTask->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateTask->errors()
                ], 401);
            }
    
            Task::create([
                'nama_task' => $request->nama_task,
                'nama_user' => $request->nama_user,
                'nim_or_nip_task' => $request->nim_or_nip_task,
                'id_kelompok' => $request->id_kelompok,
                // Tambah kolom lain yang diperlukan
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Task berhasil ditambahkan'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    ///delete task/////
    public function delete(Request $request)
    {
        try {
            $validateTask = Validator::make($request->all(), [
                'id_task' => 'required|exists:task,id_task'
            ]);

            if ($validateTask->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateTask->errors()
                ], 401);
            }

            $task = Task::find($request->id_task);

            if (!$task) {
                return response()->json([
                    'status' => false,
                    'message' => 'Task tidak ditemukan'
                ], 404);
            }

            $task->delete();

            return response()->json([
                'status' => true,
                'message' => 'Task berhasil dihapus'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    //show by id kelompok///
    public function showByIdKelompok(Request $request)
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

            $tasks = Task::where('id_kelompok', $request->id_kelompok)->get();

            if ($tasks->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data task tidak ditemukan untuk kelompok ini'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $tasks
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    //update
    public function update(Request $request)
    {
        try {
            $validateTask = Validator::make($request->all(), [
                'id_task' => 'required|exists:task,id_task',
                'nama_task' => 'required',
                'nama_user' => 'required',
                'nim_or_nip_task' => 'required',
                'id_kelompok' => 'required|exists:kelompok,id_kelompok'
                // Tambahkan validasi atau kolom lain yang diperlukan
            ]);

            if ($validateTask->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateTask->errors()
                ], 401);
            }

            $task = Task::find($request->id_task);

            if (!$task) {
                return response()->json([
                    'status' => false,
                    'message' => 'Task tidak ditemukan'
                ], 404);
            }

            $task->nama_task = $request->nama_task;
            $task->nama_user = $request->nama_user;
            $task->nim_or_nip_task = $request->nim_or_nip_task;
            $task->id_kelompok = $request->id_kelompok;
            // Update kolom lain yang diperlukan

            $task->save();

            return response()->json([
                'status' => true,
                'message' => 'Task berhasil diperbarui'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
