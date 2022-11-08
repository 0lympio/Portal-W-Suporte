<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:uploads.store')->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $files = Upload::all();

        return response()->json(['data' => $files]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $files = $request->file('files');

        foreach ($files as $file) {
            $path = $file->store('uploads', 'public');

            $data = [
                'user_id' => auth()->user()->id,
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'extension' => $file->getClientOriginalExtension(),
                'mimetype' => $file->getClientMimeType(),
                'size' => $file->getSize()
            ];

            Upload::create($data);
        }

        return back()->with('message', 'Arquivos enviados com sucesso!');
    }

    /**
     * Remove one or more resources from storage
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $files = $request->data;
        $ids = [];

        foreach ($files as $key => $file) {
            $ids[] = $file['id'];
        }

        Upload::destroy($ids);

        return response()->json(['success' => 'Files successfully removed']);
    }
}
