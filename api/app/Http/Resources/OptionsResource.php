<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// custom Resources Start
// use App\Http\Resources\Url;
// use App\Http\Resources\Date;
// custom Resources End

// use App\Http\Controllers\LessonsController;

class OptionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public $preserveKeys = false;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
