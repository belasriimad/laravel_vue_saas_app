<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->product_image,
            'description' => $this->description,
            'positives' => $this->positives,
            'negatives' => $this->negatives,
            'score' => round($this->positives->count() / ($this->positives->count() + $this->negatives->count()) * 100)
        ];
    }
}
