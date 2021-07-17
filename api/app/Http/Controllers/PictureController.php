<?php

namespace App\Http\Controllers;

use App\Imports\PicturesImport;
use App\Models\Picture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PictureController extends Controller
{
    /**
     * Get all pictures and their exif
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $pictures = Picture::all();

        if (!empty($pictures)) {
            return response()->json([
                'success' => true,
                'code' => 200,
                'error' => '',
                'results' => $pictures,
            ], 200);
        }

        return response()->json([
            'success' => true,
            'code' => 204,
            'error' => 'No results',
            'results' => [],
        ], 204);
    }

    /**
     * Get a single picture and its exif
     *
     * @param Picture $picture
     * @return Picture
     */
    public function show(Picture $picture): Picture
    {
        return $picture;
    }

    /**
     * Upload a new csv
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required|mimes:csv,txt',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'file' => '',
                'errors' => '',
            ], 401);
        }

        if ($file = $request->file('file')) {
            // does the same as Excel::import(new PicturesImport, $file); but allows me to easily get the import errors
            $import = new PicturesImport;
            $import->import($file);

            $errors = [];
            foreach ($import->failures() as $failure) {
                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            return response()->json([
                'success' => true,
                'message' => (count($errors) > 0) ? 'File successfully uploaded, but with errors.' : 'File successfully uploaded',
                'file' => $file->getClientOriginalName(),
                'errors' => $errors,
            ], 201);
        }
    }
}
