<?php

namespace App\Http\Requests\Blog;

use App\Models\Blog;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user('sanctum')->can('create', [Blog::class, request()->channel]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'catagory_id' => ['required', 'integer', 'exists:catagories,id'],
            'images' => ['array', 'sometimes'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function failedValidation(Validator $validator){
        $errors = $validator->errors();
        
        $response = response([
            'errors' => $errors->messages()
        ], 422);

        throw new HttpResponseException($response);
    }
}
