<?php

declare(strict_types=1);

namespace App\API\V1\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ReparationRequestMaterial */
class ReparationRequestMaterialResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'uuid'         => $this->uuid,
            'name'         => $this->name,
            'is_mandatory' => $this->is_mandatory,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,

            'reparation_request_id' => $this->reparation_request_id,

            'reparation_request' => new ReparationRequestResource($this->whenLoaded('reparationRequest')),
        ];
    }
}
