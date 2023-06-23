<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class UpdateUserValidator
{
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|exists:users,id',
            'status' => 'required|in:1,2,3',
            'account_code' => 'nullable|string'
        ]);

        return $validator->validate();
    }
}
