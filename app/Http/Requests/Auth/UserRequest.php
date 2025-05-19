<?php

namespace App\Http\Requests\Auth;

use App\Enums\Users\GenderEnum;
use App\Enums\Users\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UserRequest extends FormRequest
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
        $id = $this->route('user');

        return [
            'name' => 'required',
            'email' => 'required|email|string|unique:users,email,' . $id,
            'phone' => 'required',
            'gender' => ['required', 'string', new Enum(GenderEnum::class)],
            'role' => ['required', 'string', new Enum(RoleEnum::class)],
        ];
    }

}
