<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'password'              => 'required|string|max:30|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/|confirmed',
            'password_confirmation' => 'required',
            'old_password'          => 'required',
        ];
    }
}
