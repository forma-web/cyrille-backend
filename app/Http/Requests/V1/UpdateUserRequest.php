<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', 'unique:users'],
            'password' => ['string', 'max:255', 'confirmed', Password::default()],
            'password_confirmation' => ['required_with:password', 'string'],
            'current_password' => ['required_with:password', 'string', 'max:255', 'current_password'],
        ];
    }
}
