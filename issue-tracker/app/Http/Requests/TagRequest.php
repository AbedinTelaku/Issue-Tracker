<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tagId = $this->route('tag');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags', 'name')->ignore($tagId),
            ],
            'color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'color_hex' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The tag name is required.',
            'name.max' => 'The tag name may not be greater than 255 characters.',
            'name.unique' => 'A tag with this name already exists.',
            'color.max' => 'The color value is too long.',
            'color.regex' => 'The color must be a valid hex color code (e.g., #FF5733).',
        ];
    }
}
