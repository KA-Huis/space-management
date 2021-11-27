<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Space;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_open_for_reservations' => [
                'required',
                'boolean',
            ],
        ];
    }
}
