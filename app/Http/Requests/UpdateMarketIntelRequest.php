<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarketIntelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|min:2",
                "description" => "required|min:2",
                "image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=265,height=200",
                "short_description" => "required|min:2|max:100",
                "url"=>"nullable|url"
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        logger($validator->errors()->getMessageBag()->toJson());
        throw new HttpResponseException(redirect()->back()->withErrors($validator)->withInput());
    }
    public function messages(){
        return
            ['image.dimensions' => 'Please upload image with required dimensions', 'image.max' => 'The image may not be greater than 5MB'];
        
    }
}
