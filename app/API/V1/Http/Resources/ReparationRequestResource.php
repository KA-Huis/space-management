<?php

declare(strict_types=1);

namespace App\API\V1\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReparationRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'priority'    => $this->priority,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'deleted_at'  => $this->deleted_at,
            'reporter_id' => $this->reporter_id,
            'reporter'    => new PublicUserResource($this->whenLoaded('reporter')),
            'statuses'    => new ReparationRequestStatusCollection($this->whenLoaded('statuses')),
            'materials'   => new ReparationRequestMaterialCollection($this->whenLoaded('materials')),
        ];
    }
}
