<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventModel extends Model {
  protected $table = 'events';
  protected $guarded = ['created_at', 'updated_at', 'date'];

  public function action()
  {
      return new Managers\Actioner($this);
  }

  public function assign()
  {
      return new Managers\Assigner($this);
  }

  public function validate()
  {
      return new Managers\Validator($this);
  }
}