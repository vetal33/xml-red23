<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class JsonUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'jsonFile' => [
                'required',
                File::types(['json'])
                    ->max(50 * 1024),
            ]

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
