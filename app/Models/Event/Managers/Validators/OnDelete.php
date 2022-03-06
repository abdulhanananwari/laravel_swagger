<?php

namespace App\Models\Event\Managers\Validators;

use App\Models\Event\EventModel;

class OnDelete
{
    protected $event;

    public function __construct(EventModel $event)
    {
        $this->event = $event;
    }

    public function validate()
    {

        if (date('Y-m-d') < $this->event->date) {
          return ["Can't delete event"];
        }
        return true;
    }

}
