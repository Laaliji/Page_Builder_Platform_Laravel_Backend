<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'idP'         => $this->idP,
            'title'       => $this->title,
            'description' => $this->description,
            'domaineName' => $this->domaineName,
            'repository'  => $this->repository,
            'image_url'   => $this->image_url,
            'created_at'  => $this->created_at,
            'shared_link'  => $this->shared_link,
        ];
    }
}
