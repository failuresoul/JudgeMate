<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ProblemRequest extends FormRequest
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
        $problem = $this->route('problem');
        $problemId = $problem ? $problem->id : null;

        return [
            'title'         => ['required', 'string', 'max:255'],
            'slug'          => ['nullable', 'string', 'max:255', Rule::unique('problems', 'slug')->ignore($problemId)],
            'statement'     => ['required', 'string'],
            'input_format'  => ['nullable', 'string'],
            'output_format' => ['nullable', 'string'],
            'constraints'   => ['nullable', 'string'],
            'difficulty'    => ['required', Rule::in(['easy', 'medium', 'hard'])],
            'is_published'  => ['nullable', 'boolean'],
            'tags'          => ['nullable', 'array'],
            'tags.*'        => ['integer', 'exists:tags,id'],
        ];
    }
}
