<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarModelResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id'         => $this->id,
			'year'       => $this->modelYear->name,
			'brand_name' => $this->brand->name,
			'name'       => $this->name,
			'image'      => $this->image,
		];
	}
}
