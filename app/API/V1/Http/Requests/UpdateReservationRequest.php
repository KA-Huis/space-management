<?php

declare(strict_types=1);

namespace App\API\V1\Http\Requests;

use App\Models\Enums\ReparationRequestPriority;
use App\Models\Group;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'starts_at' => [
                'required',
                'string',
                'date',
            ],
            'ends_at' => [
                'required',
                'string',
                'date',
                'after:today',
            ],
            'space_id' => [
                'required',
                'numeric',
                Rule::exists((new Space())->getTable(), 'id'),
            ],
            'created_by_user_id' => [
                'required',
                'numeric',
                Rule::exists((new User())->getTable(), 'id'),
            ],
            'group_id' => [
                'required',
                'numeric',
                Rule::exists((new Group())->getTable(), 'id'),
            ],
        ];
    }
}
