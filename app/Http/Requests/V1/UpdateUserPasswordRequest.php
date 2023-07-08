<?php

namespace App\Http\Requests\V1;

use App\Rules\CheckCurrentPassword;
use Illuminate\Validation\Rules\Password;

class UpdateUserPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string', 'max:255', new CheckCurrentPassword()],
            'password' => ['required', 'string', 'max:255', 'confirmed', Password::default()],
            'password_confirmation' => ['required', 'string'],
        ];
    }
}
