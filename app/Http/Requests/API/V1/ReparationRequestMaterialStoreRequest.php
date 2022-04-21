<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1;

use App\Models\ReparationRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
