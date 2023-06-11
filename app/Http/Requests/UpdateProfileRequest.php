<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "first_name" => "required|min:2",
            "last_name" => "required|min:2",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=200,max_height=200"
        ];
    }
    public function messages()
    {
        return ['image.dimensions' => 'Please upload image with required dimensions', 'image.max' => 'The image may not be greater than 2MB'];
    }
    
}
