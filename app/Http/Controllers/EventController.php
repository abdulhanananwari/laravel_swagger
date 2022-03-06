<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event\EventModel;
use App\Models\Event\Transformers\EventTransformer;
use App\Helpers\Controllers\ApiBaseController as Controller;


class EventController extends Controller {
  
  protected $event;

  public function __construct()
  {
    parent::__construct();

    $this->event = new EventModel();
    $this->transformer = new EventTransformer;

  
  }
    /**
     * @OA\Get(
     *     path="/api/event",
     *     summary="List all event",
     *     operationId="index",
     *     tags={"Event"},
     * 
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         example=1,
     *         in="query",
     *         description="number of page",
     *         required=true,
     *      ),
     *      @OA\Parameter(
     *         name="paginate",
     *         example=10,
     *         in="query",
     *         description="limit data per page",
     *         required=true,
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="An paged array of event",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/EventResponse")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
  public function index(Request $request){
    
    $query = $this->event->newQuery();
      
    if ($request->has('paginate')) {
          $events = $query->paginate((int) $request->get('paginate', 15));
          return $this->formatCollection($events, [], $events);
      } else {
          $events = $query->get();
          return $this->formatCollection($events);
      }
  }

   /**
     * @OA\Get(
     *     path="/api/event/{id}",
     *     summary="Get a event by id",
     *     tags={"Event"},
     *
     *     operationId="get",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Parameter with id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value.")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
   */

  public function show($id){

    $event = $this->event->find($id);
    return $this->formatItem($event);
  
  }

     /**
     * @OA\Post(
     *   path="/api/event",
     *   tags={"Event"},
     *   summary="Create New Event",
     *   description="Create New Event",
     *   operationId="store",
     *     security={{"bearer":{}}},
     * 
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *            mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="name",
     *                   description="Name Events",
     *                   type="string",
     *                   example="kodegiri event 2022"
     *               ),
     *              @OA\Property(
     *                   property="date",
     *                   description="Date Events",
     *                   type="string",
     *                   format="date",
     *                   example="2022-02-01"
     * 
     *               ),
     *              @OA\Property(
     *                   property="time",
     *                   description="Time Events",
     *                   type="string",
     *                   example="13:00"
     * 
     *               ),
     *               @OA\Property(
     *                   property="file",
     *                   description="image event",
     *                   type="file"
     *               ),
     *           )
     *       )
     *   ),
     *  @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="int",
     *                         description="id event"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="name event"
     *                     ),
     *                     example={
     *                         "id": 1,
     *                         "name": "event kodegiri 2022",
     *                         "date": "2022-02-02",
     *                          "time": "17:00",
     *                          "image":"image.jpg"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *   @OA\Response(response="401",description="Unauthorized"),
     * )
     */


  public function store(Request $request){

    $event = $this->event->assign()->fromRequest($request);

    $validation = $event->validate()->onCreateAndUpdate();
    if ($validation !== true) {
        return $this->formatErrors($validation);
    }
    $event->action()->onCreateAndUpdate();

    return $this->formatItem($event);

  }

       /**
     * @OA\Post(
     *   path="/api/event/{id}",
     *   tags={"Event"},
     *   summary="Update  Event By Id",
     *   description="Update Event By Id",
     *   operationId="update",
     *   security={{"bearer":{}}},
     *   @OA\Parameter(
     *         name="_method",
     *         in="query",
     *         description="for replace method POST into PUT",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="PUT",summary="value query string")
     *   ),
     *    @OA\Parameter(
     *         description="Parameter with id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value.")
     *   ),
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *            mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="name",
     *                   description="Name Events",
     *                   type="string",
     *                   example="kodegiri event 2022"
     *               ),
     *              @OA\Property(
     *                   property="date",
     *                   description="Date Events",
     *                   type="string",
     *                   format="date",
     *                   example="2022-02-01"
     * 
     *               ),
     *              @OA\Property(
     *                   property="time",
     *                   description="Time Events",
     *                   type="string",
     *                   example="13:00"
     * 
     *               ),
     *               @OA\Property(
     *                   property="file",
     *                   description="image event",
     *                   type="file"
     *               ),
     *           )
     *       )
     *   ),
     *  @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="id",
     *                         type="int",
     *                         description="id event"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         description="name event"
     *                     ),
     *                     example={
     *                         "id": 1,
     *                         "name": "event kodegiri 2022",
     *                         "date": "2022-02-02",
     *                          "time": "17:00",
     *                          "image":"image.jpg"
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *   @OA\Response(response="401",description="Unauthorized"),
     * )
     */

  public function update(Request $request, $id){

    $event = $this->event->find($id);

    $event->assign()->fromRequest($request);

    $validation = $event->validate()->onCreateAndUpdate();
    if ($validation !== true) {
        return $this->formatErrors($validation);
    }
    $event->action()->onCreateAndUpdate();

    return $this->formatItem($event);
  }

     /**
     * @OA\Delete(
     *     path="/api/event/{id}",
     *     summary="Delete a event by id",
     *     tags={"Event"},
     *
     *     operationId="delete",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Parameter with id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value.")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
   */

  public function delete($id){

    $event = $this->event->find($id);

    $validation = $event->validate()->onDelete();
    if ($validation !== true) {
        return $this->formatErrors($validation);
    }
    $event->action()->onDelete();

    return response()->json('OK');
  }
}