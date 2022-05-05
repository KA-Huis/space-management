<?php

declare(strict_types=1);

namespace App\API\V1\Http\Requests;

use App\Models\GroupType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends FormRequest
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
            'group_type_id' => [
                'required',
                'numeric',
                Rule::exists((new GroupType())->getTable(), 'id'),
            ],
        ];
    }
}
