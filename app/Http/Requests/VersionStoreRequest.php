<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VersionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:2100',
            'rich_content' => 'required|string',
            'attachment_file' => 'nullable|file|mimes:pdf|max:10240', // max 10MB
        ];
    }
}
