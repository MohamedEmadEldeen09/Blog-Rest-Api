<?php

namespace App\Http\Requests\Channel;

use App\Models\Channel;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->can('create', Channel::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:channels,name',
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
