<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
        return [
            'issue_id' => 'required|exists:issues,id',
            'author_name' => 'required|string|max:255',
            'body' => 'required|string|min:3',
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
            'issue_id.required' => 'The issue ID is required.',
            'issue_id.exists' => 'The selected issue does not exist.',
            'author_name.required' => 'The author name is required.',
            'author_name.max' => 'The author name may not be greater than 255 characters.',
            'body.required' => 'The comment body is required.',
            'body.min' => 'The comment must be at least 3 characters long.',
        ];
    }
}
