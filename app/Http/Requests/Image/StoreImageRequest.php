<?php

namespace App\Http\Requests\Image;

use App\Enums\ImageOwnerEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        dd(request()->file('avatar'));
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
            'image' => 'required|image',
        ];
    }

    public function failedValidation(Validator $validator){
        $errors = $validator->errors();

        $response = response()->json([
            'errors' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
