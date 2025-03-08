<?php

namespace App\Http\Requests;

use App\Enums\Items\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ItemRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'location' => 'nullable|string',
            'status' => ['required', 'string', new Enum(StatusEnum::class)],
            'image' => 'nullable|image|max:2048',
            'user_id' => 'required|exists:users,id',
            'found_date' => 'nullable|date',
        ];
    }
}
