<?php

namespace App\Http\Controllers;

use App\Models\ParameterImage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ParameterController extends Controller
{
    public function index(Request $request): View|Factory|Application
    {
        $parametersWithImages = [];
        try {
            $parametersWithImages = getAllParametersWithImages($request->searchText);
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }

        return view('home', [
            'parameters' => $parametersWithImages
        ]);
    }

    public function uploadParameterImg(Request $request): JsonResponse
    {
        $response = response()->json(['error' => 'Image upload failed.']);
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
            ]);

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $parameterId = (int)$request->get('parameter_id');
                list($imageName, $imageUrl) = uploadingParameterImg($imageFile);
                $imageType = $request->get('image_type');
                $parameterImage = new ParameterImage();
                $parameterImage->name = $imageName;
                $parameterImage->image_type = $imageType;
                $parameterImage->url = $imageUrl;
                $parameterImage->parameter_id = $parameterId;
                $parameterImage->save();
                $response = response()->json(['success' => true]);
            }
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }

        return $response;
    }

    public function updateParameterImg(Request $request): JsonResponse
    {
        $response = response()->json(['error' => 'Image upload failed.']);
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image_id' => 'required',
            ]);

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageId = (int)$request->get('image_id');
                list($imageName, $imageUrl) = uploadingParameterImg($imageFile);
                $parameterImage = ParameterImage::findOrFail($imageId);
                deletingParameterImg($parameterImage->name);
                $parameterImage->name = $imageName;
                $parameterImage->url = $imageUrl;
                $parameterImage->save();
                $response = response()->json(['url' => $imageUrl]);
            }
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }

        return $response;
    }

    public function deleteParameterImg(Request $request): JsonResponse
    {
        $deleteStatus = false;
        try {
            $imageId = (int)$request->get('image_id');
            $parameterImage = ParameterImage::findOrFail($imageId);
            deletingParameterImg($parameterImage->name);
            $parameterImage->delete();
            $deleteStatus = true;
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }

        return response()->json(['success' => $deleteStatus]);
    }
}
