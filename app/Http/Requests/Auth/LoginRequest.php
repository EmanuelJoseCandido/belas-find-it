<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('type')) {
            $type = $this->input('type');

            if ($type === 'phone') {
                $inputPhone = $this->input('phone');
                $phone = preg_replace('/[^0-9]/', '', $inputPhone);
                $this->merge(['phone' => $phone]);
            } else {
                $email = $this->input('email');
                $this->merge(['email' => $email]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['email', 'phone'])],
            'email' => ['sometimes', 'email', 'max:255'],
            'phone' => ['sometimes', 'string', 'min:10', 'max:15'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }
}
