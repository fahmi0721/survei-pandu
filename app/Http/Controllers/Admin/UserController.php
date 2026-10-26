<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Requests\User\ResetUserPasswordRequest;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }

    public function resetPassword(
        ResetUserPasswordRequest $request,
        User $user
    ): JsonResponse {

        abort_unless(
            $user->role === 'hrd',
            403,
            'Hanya password akun HRD yang dapat direset.'
        );

        $data = $request->validated();

        $user->forceFill([
            'password' => $data['password'],
            'remember_token' => Str::random(60),
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Password HRD berhasil direset.',
        ]);
    }
    
    public function data(): JsonResponse
    {
        return response()->json([
            'data' => User::query()
                ->where('role', 'hrd')
                ->select('id', 'name', 'email', 'role', 'created_at')
                ->latest()
                ->get(),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'hrd',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun HRD berhasil ditambahkan.',
            'data' => $user->only('id', 'name', 'email', 'role'),
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        abort_unless($user->role === 'hrd', 404);

        return response()->json([
            'data' => $user->only('id', 'name', 'email', 'role'),
        ]);
    }

    public function update(
        UpdateUserRequest $request,
        User $user
    ): JsonResponse {
        abort_unless($user->role === 'hrd', 403);

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Akun HRD berhasil diperbarui.',
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        abort_unless($user->role === 'hrd', 403);

        // Tidak menghapus data responden atau hasil survei.
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun HRD berhasil dihapus.',
        ]);
    }
}