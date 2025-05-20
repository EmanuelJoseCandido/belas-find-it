<?php

namespace App\Http\Requests;

use App\Enums\Items\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ItemFilterRequest extends FormRequest
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
            'search' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'location' => 'nullable|string',
            'date' => 'nullable|date',
            'status' => ['nullable', 'string', new Enum(StatusEnum::class)],
        ];
    }
}
