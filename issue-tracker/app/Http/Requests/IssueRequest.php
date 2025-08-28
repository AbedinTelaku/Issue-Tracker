<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
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
            'project_id.required' => 'Please select a project.',
            'project_id.exists' => 'The selected project does not exist.',
            'title.required' => 'The issue title is required.',
            'title.max' => 'The issue title may not be greater than 255 characters.',
            'description.required' => 'The issue description is required.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The selected status is invalid.',
            'priority.required' => 'Please select a priority.',
            'priority.in' => 'The selected priority is invalid.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after_or_equal' => 'The due date must be today or a future date.',
            'tags.array' => 'Tags must be an array.',
            'tags.*.exists' => 'One or more selected tags do not exist.',
        ];
    }
}
