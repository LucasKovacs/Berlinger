<?php

namespace App\Http\Controllers;

use App\Imports\PicturesImport;
use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PictureController extends Controller
{
    public function index()
    {
        return Picture::all();
    }

    public function show(Picture $picture)
    {
        return $picture;
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required|mimes:csv,txt',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if ($files = $request->file('file')) {
            $result = Excel::import(new PicturesImport, $files);

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $files->getClientOriginalName(),
            ], 201);
        }
    }
}
