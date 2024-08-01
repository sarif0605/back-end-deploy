<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'age' => 'nullable|integer',
            'bio' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'age.nullable' => 'Age is nullable',
            'age.integer' => 'Age must be an integer',
            'bio.nullable' => 'Bio is nullable',
            'bio.string' => 'Bio must be a string',
        ];
    }
}
