<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialLinkResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'social_key' => $this->social_key,
            'url'        => $this->url,
            'icon'       => $this->icon,
        ];
    }
}
