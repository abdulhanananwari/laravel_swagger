<?php

namespace App\Models\Event\Managers;

use App\Helpers\Managers\ManagerBase as Manager;
use App\Models\Event\EventModel;

class Actioner extends Manager
{
    protected $event;

    public function __construct(EventModel $event)
    {
        $this->event = $event;
    }

    public function __call($name, $arguments)
    {
        return $this->managerCaller($name, $arguments, $this->event, __NAMESPACE__, 'Actioners', 'action');
    }
}
