<?php

declare(strict_types=1);

namespace App\API\V1\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReparationRequestMaterialCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}