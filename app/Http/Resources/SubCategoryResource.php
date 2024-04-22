<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category = new CategoryResource(
            $this->whenLoaded('category')
        );

        $data = $this->relatedRss($category, 'category_id', 'category');

        return [
            'id' => $this->id,
            'type',
            ...$data,
            'category_id'
        ];
    }
}
