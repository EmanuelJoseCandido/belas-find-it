<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'                 => 'required|email',
            'password'              => 'required|string|max:30|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/|confirmed',
            'password_confirmation' => 'required',
            'token'                 => 'required|string',
            'recaptcha_token'       => 'sometimes|string'
        ];
    }
}
