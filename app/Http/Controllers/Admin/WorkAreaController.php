<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkArea\StoreWorkAreaRequest;
use App\Http\Requests\WorkArea\UpdateWorkAreaRequest;
use App\Models\WorkArea;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class WorkAreaController extends Controller
{
    public function index(): View
    {
        return view('admin.work-areas.index');
    }

    public function data(): JsonResponse
    {
        return response()->json([
            'data' => WorkArea::query()
                ->orderBy('sort_order')
                ->orderBy('nama')
                ->get(),
        ]);
    }

    public function store(
        StoreWorkAreaRequest $request
    ): JsonResponse {

        $area = DB::transaction(function () use ($request) {

            $data = $request->validated();

            // Serialisasi penambahan urutan dengan lock tabel
            $data['sort_order'] =
                (int) WorkArea::max('sort_order') + 1;

            return WorkArea::create($data);
        });

        return response()->json([
            'success' => true,
            'message' => 'Wilayah kerja berhasil ditambahkan.',
            'data' => $area,
        ], 201);
    }

    public function show(WorkArea $workArea): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $workArea,
        ]);
    }

    public function update(
        UpdateWorkAreaRequest $request,
        WorkArea $workArea
    ): JsonResponse {

        $workArea->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Wilayah kerja berhasil diperbarui.',
            'data' => $workArea->refresh(),
        ]);
    }

    public function destroy(WorkArea $workArea): JsonResponse
    {
        // Nonaktifkan, jangan hapus agar referensi
        // jawaban responden nantinya tetap terjaga.
        $workArea->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Wilayah kerja berhasil dinonaktifkan.',
        ]);
    }
}