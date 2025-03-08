<?php

namespace App\Http\Requests;

use App\Enums\Claims\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ClaimRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
            'status' => ['required', 'string', new Enum(StatusEnum::class)],
            'message' => 'nullable|string',
        ];
    }
}
