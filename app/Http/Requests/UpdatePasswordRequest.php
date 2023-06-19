<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePasswordRequest extends FormRequest
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
            'old_password' => 'required|string|max:20',
            'new_password' => 'required|string|max:20|strong_password|different:old_password',
            'confirm_password' => 'required|same:new_password'
        ];
    }

    public function messages()
    {
        return ['new_password.different' => 'The new password and Current password must be different.'];
    }
    protected function failedValidation(Validator $validator)
    {
        $redirect = Redirect::back()
            ->withErrors($validator)
            ->withInput()
            ->with('tab', 'password');
        throw new HttpResponseException($redirect);
    }
}
