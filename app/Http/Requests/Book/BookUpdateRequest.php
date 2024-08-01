<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
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
            'title' => 'nullable',
            'summary' => 'nullable',
            'image' => 'mimes:jpeg,png,jpg,|max:2048|nullable',
            'stock' => 'nullable',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.nullable' => 'The title field is nullable.',
            'summary.nullable' => 'The summary field is nullable.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
            'category_id.nullable' => 'The category field is nullable.',
            'category_id.exists' => 'The category does not exist.',
            'stock.nullable' => 'The stock field is nullable.',
        ];
    }
}
