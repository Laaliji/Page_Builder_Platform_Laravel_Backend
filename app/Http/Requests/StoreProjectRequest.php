<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'domaineName' => 'required|string|max:255|unique:projects,domaineName',
            'repository' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Project title is required.',
            'description.required' => 'Project description is required.',
            'domaineName.required' => 'Domain name is required.',
            'domaineName.unique' => 'This domain name is already taken.',
            'repository.url' => 'Repository URL must be valid.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Only JPEG, PNG, JPG, and WebP formats are allowed.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}