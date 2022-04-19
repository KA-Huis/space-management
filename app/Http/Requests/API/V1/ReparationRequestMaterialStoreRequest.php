<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReparationRequestMaterialStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'is_mandatory'          => ['required', 'boolean'],
            'reparation_request_id' => ['required', 'exists:App\Models\ReparationRequest,id'],
        ];
    }
}
