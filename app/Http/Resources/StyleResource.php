<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StyleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'tertiary_color' => $this->tertiary_color,
            'quaternary_color' => $this->quaternary_color,
            'background_color' => $this->background_color,
            'text_color' => $this->text_color,
            'is_dark_mode' => $this->is_dark_mode
        ];
    }
}