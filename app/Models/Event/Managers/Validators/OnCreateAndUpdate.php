<?php

namespace App\Models\Event\Managers\Validators;

use App\Models\Event\EventModel;

class OnCreateAndUpdate
{
    protected $event;

    public function __construct(EventModel $event)
    {
        $this->event = $event;
    }

    public function validate()
    {

        $attrValidation = $this->validateAttributes();
        if ($attrValidation->fails()) {
            return $attrValidation->errors()->all();
        }

        return true;
    }

    protected function validateAttributes()
    {
        return \Validator::make($this->event->toArray(), [
              'name' => 'required',
              'date' => 'required',
        ]);
    }
}
