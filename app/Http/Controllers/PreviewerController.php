<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class PreviewerController extends Controller
{
    public function index(Request $request): View
    {
        return view('previewer.index');
    }

    public function store(Request $request) : JsonResponse
    {
        $fileName = 'image';

        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile($fileName)) {
            $image = $request->file($fileName);
            $name = 'preview-image.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');

            $fullPath = $destinationPath . '/' . $name;
            if(file_exists($fullPath))
            {
                unlink($fullPath);
            }

            $image->move($destinationPath, $name);

            return response()->json([
                'path' => URL::asset("/images/{$name}")
            ]);
        }
        return response()->json([
            'error' => 'no image is created'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

    }
}