<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'video' => ['required', 'mimetypes:video/mp4,video/mpeg,video/quicktime', 'max:51200'], // 50MB max
        ];
    }
}
