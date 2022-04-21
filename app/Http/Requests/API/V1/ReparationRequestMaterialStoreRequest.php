<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1;

use Illuminate\Validation\Rule;
use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use Illuminate\Foundation\Http\FormRequest;

class ReparationRequestMaterialStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'is_mandatory'          => ['required', 'boolean'],
            'reparation_request_id' => ['required', Rule::exists((new ReparationRequest())->getTable(), 'id')],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
