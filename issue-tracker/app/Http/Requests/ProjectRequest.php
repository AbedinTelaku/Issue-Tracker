<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
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
            'name.required' => 'The project name is required.',
            'name.max' => 'The project name may not be greater than 255 characters.',
            'description.required' => 'The project description is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'deadline.date' => 'The deadline must be a valid date.',
            'deadline.after_or_equal' => 'The deadline must be after or equal to the start date.',
        ];
    }
}
