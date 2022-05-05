<?php

declare(strict_types=1);

namespace App\API\V1\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'id'             => $this->id,
            'name'           => $this->name,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'deleted_at'     => $this->deleted_at,
            'group_type_id'  => $this->group_type_id,
            'group_type'     => new GroupTypeResource($this->whenLoaded('groupType')),
        ];
    }
}
