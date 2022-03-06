<?php

namespace App\Models\Event\Transformers;

use League\Fractal;
// use Leaguage
use App\Models\Event\EventModel;
/**
 * @OA\Schema(
 *     schema="EventResponse",
 *     type="object",
 *     title="EventResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="date", type="string"),
 *         @OA\Property(property="time", type="string"),
 *         @OA\Property(property="image", type="string"),
 *     }
 * )
 */
class EventTransformer extends Fractal\TransformerAbstract
{

    public function transform(EventModel $event)
    {

        $data = [

            'id' => $event->id,
            'name' => $event->name,
            'date' => $event->date,
            'time' => $event->time,
            'image' => $event->image,
        ];

        return $data;
    }
}
