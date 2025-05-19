<?php

namespace App\Http\Requests\Auth;

use App\Enums\Users\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|string|unique:users,email',
            'phone' => 'required',
            'gender' => ['required', 'string', new Enum(GenderEnum::class)],
            'password' => 'required|string|max:30|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/|confirmed',
            'password_confirmation' => 'required',
        ];
    }
}
