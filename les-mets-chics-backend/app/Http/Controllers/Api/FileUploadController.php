<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Requests\VideoUploadRequest;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    /**
     * @OA\Post(
     *     path="/upload/image",
     *     tags={"Uploads"},
     *     summary="Upload d'image",
     *     description="Upload une image (jpeg, png, jpg, webp) max 5MB",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Upload réussi",
     *         @OA\JsonContent(
     *             @OA\Property(property="url", type="string", example="http://localhost:8000/storage/uploads/images/xyz.jpg"),
     *             @OA\Property(property="path", type="string", example="uploads/images/xyz.jpg")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation (format ou taille)"),
     *     @OA\Response(response=400, description="Aucun fichier envoyé")
     * )
     */
    public function uploadImage(ImageUploadRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/images', 'public');
            
            return response()->json([
                'url' => Storage::url($path),
                'path' => $path,
            ], 201);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }

    /**
     * @OA\Post(
     *     path="/upload/video",
     *     tags={"Uploads"},
     *     summary="Upload de vidéo",
     *     description="Upload une vidéo (mp4, mpeg, quicktime) max 50MB",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="video", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Upload réussi",
     *         @OA\JsonContent(
     *             @OA\Property(property="url", type="string", example="http://localhost:8000/storage/uploads/videos/xyz.mp4"),
     *             @OA\Property(property="path", type="string", example="uploads/videos/xyz.mp4")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation (format ou taille)"),
     *     @OA\Response(response=400, description="Aucun fichier envoyé")
     * )
     */
    public function uploadVideo(VideoUploadRequest $request)
    {
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('uploads/videos', 'public');
            
            return response()->json([
                'url' => Storage::url($path),
                'path' => $path,
            ], 201);
        }

        return response()->json(['message' => 'No video uploaded'], 400);
    }
}
