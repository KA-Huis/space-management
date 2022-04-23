<?php

declare(strict_types=1);

namespace App\API\V1\Http\Requests;

use App\Models\Enums\ReparationRequestPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReparationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
            ],
            'priority' => [
                'nullable',
                'integer',
                Rule::in(ReparationRequestPriority::ALL_PRIORITIES),
            ],
        ];
    }
}
