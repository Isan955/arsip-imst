<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Checked via Policies/Gates
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'summary' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,webp',
            'status' => 'required|in:draft,published',
            // Initial version inputs
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2000|max:2100',
            'rich_content' => 'nullable|string',
            'attachment_file' => 'nullable|file|mimes:pdf|max:10240', // max 10MB
        ];
    }
}
