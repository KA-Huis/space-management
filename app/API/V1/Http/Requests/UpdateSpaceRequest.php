<?php

declare(strict_types=1);

namespace App\API\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpaceRequest extends FormRequest
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
                'required',
                'string',
            ],
            'is_open_for_reservations' => [
                'required',
                'boolean',
            ],
        ];
    }
}
