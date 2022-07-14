<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// custom Resources Start
// use App\Http\Resources\Url;
use App\Http\Resources\Date;
// custom Resources End

// use App\Http\Controllers\CoursesController;

class QuizzsResource extends JsonResource
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
            'title' => $this->title,
            'order' => $this->order,
            'permalink' => $this->permalink,
            'description' => $this->description,
            'thumbnails' => $this->thumbnails,
            'created_at' => Date::DateFr($this->created_at),
            'updated_at' => Date::DateFr($this->updated_at),
            'status' => $this->status,
            'title_seo' => $this->title_seo,
            'meta_seo' => $this->meta_seo,
            'json'  => $this->json
        ];
    }
}
