<?php

namespace App\Models\Event\Managers\Assigners;

use Illuminate\Http\Request;
use App\Models\Event\EventModel;

class FromRequest
{

    protected $event;

    public function __construct(EventModel $event)
    {
        $this->event = $event;
    }

    public function assign(Request $request)
    {
        $this->event->fill($request->only([
            'name','time'
        ]));

        if($request->file('file')){
            $request->validate([
              'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        
            $image = $request->file('file');
    
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
    
            $img = \Image::make($image->getRealPath());
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();                 
            });
    
            $img->stream();
    
            $image->move(public_path().'/images/', $fileName);
            $this->event->image = $fileName;
        }

        if($request->get('date')){
          $this->event->date = \Carbon\Carbon::createFromFormat('Y-m-d',$request->get('date'));
        }
        return $this->event;
    }
}
