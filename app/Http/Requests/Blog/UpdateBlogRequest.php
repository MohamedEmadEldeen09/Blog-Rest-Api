<?php

namespace App\Http\Requests\Blog;

use App\Models\Blog;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user('sanctum')->can('update', $this->blog);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
            'catagory_id' => ['sometimes', 'integer', 'exists:catagories,id'],
            //'images' => ['required', 'array', 'sometimes'],
            //'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            //'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function failedValidation(Validator $validator){
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
