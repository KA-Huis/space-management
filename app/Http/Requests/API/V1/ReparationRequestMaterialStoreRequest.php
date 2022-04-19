<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\FormRequest;

class ReparationRequestMaterialStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'boolean'],
            'reparation_request_id' => ['required', 'exists:App\Models\ReparationRequest,id'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
