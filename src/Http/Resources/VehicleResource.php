<?php

namespace Eauto\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => (string) $this->id,
            'makeId'    => $this->make_id,
            'active'    => (bool) $this->Active_flag,
            'updatedAt' => $this->updated_at?->toAtomString(),
        ];
    }
}