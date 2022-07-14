<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// use App\Http\Resources\Url;
// use App\Http\Resources\Date;
// use App\Http\Controllers\CoursesController;

class Lessons_coursesResource extends JsonResource
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
            'courses_id'	=> $this->courses_id,
            'lessons_id'	=> $this->lessons_id,
            'quizz_id'		=> $this->quizz_id,
			'order'			=> $this->order
        ];
    }
}
