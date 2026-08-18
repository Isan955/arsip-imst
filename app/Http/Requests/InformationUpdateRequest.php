<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformationUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'summary' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,webp',
            'status' => 'required|in:draft,published',
        ];
    }
}
