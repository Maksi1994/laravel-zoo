<?php

namespace App\Http\Resources\Animals;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AnimalsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        return $this->map(function ($animals) {
            return [
                'id' => $animals->id,
                'place' => $animals->place,
                'created_at' => $animals->created_at->format('d.m.Y'),
                'name' => $animals->name,
                'age' => $animals->age
            ];
        });
    }
}
