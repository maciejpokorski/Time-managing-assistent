<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Calendar;
use App\Event;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class EventController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('owner:event', ['only' => ['update', 'destroy', 'edit']]);
    }

    public function index()
    {
        $events = [];
        $categories = Auth::user()->categories()->with('events')->get();
        $categoriesArray = [];
        if($categories->count()) {
            foreach ($categories as $category) {
                $categoriesArray[$category->id] = 
                [
                    'title' => $category->title,
                    'color' => $category->color
                ];
                foreach ($category->events as $event){
                    $events[] = Calendar::event(
                        $event->title,
                        false,
                        new \DateTime($event->start_date),
                        new \DateTime($event->end_date),
                        null,
                        // Add color and link on event
                        [
                            'color' => $category->color,
                            'url' => 'events/'.$event->id,
                            'className' => [str_replace(' ', '_', $category->title)]
                        ]
                    );
                }
                
            }
        }

        $calendar = Calendar::addEvents($events)
        ->setOptions(
            [
                'themeSystem' => 'bootstrap4',
                'bootstrapFontAwesome' => [
                    'close' => 'fa-times',
                    'prev' => 'fa-chevron-left',
                    'next' => 'fa-chevron-right',
                    'prevYear' => 'fa-angle-double-left',
                    'nextYear' => 'fa-angle-double-right'
                ],
                'showNonCurrentDates' => false,
                'fixedWeekCount' => false,
                'buttonText' => [
                    'listDay' => 'list day',
                    'listWeek' =>  'list week',
                    'listMonth' => 'list month',
                    'listYear' => 'list year'
                ],
                'selectable' => false,
                'selectHelper' => false,
                'displayEventEnd' => ['month' => true],
                'buttonIcons' => true,
                'listDay' => ['text' => 'xd'],
                'footer' => [
                    'left' => 'listDay, listWeek, listMonth, listYear',
                ]
            ]
        )
        ->setCallbacks([
            'eventAfterAllRender' => 'function(view) {   

               Object.keys(hoursTotal).forEach(function (key) {
                    $("#"+key).height(hoursTotal[key])
                    $("#"+key).find(".badge").html(hoursTotal[key]+" h");
                });

            }',

            'eventRender' => 'function(event, element, view) {
                if(!hoursTotal.view || hoursTotal.view !== view.name){
                    hoursTotal[event.className[0]] = 0;
                }
                
            }',

            'eventAfterRender' => 'function(event, element, view) {
                var duration = moment.duration(event.end - event.start).hours() + moment.duration(event.end - event.start).days()*24; 
                element.find(".fc-title").append(" "+duration+"h")

                if(hoursTotal.events.indexOf(event._id) === -1){
                     hoursTotal.events.push(event._id);
                     hoursTotal[event.className[0]] += duration;
                }

                if(hoursTotal.filter){
                    if(event.className[0] !== hoursTotal.filter){
                        element.remove();
                    }
                }

                hoursTotal.view = view.name;
               
            }',
            'viewRender' => 'function(view, element) {
                hoursTotal.events = [];    
                Object.keys(hoursTotal).forEach(function (key) {
                     if(key !== "events"){
                        hoursTotal[key] = 0;
                     }
                });
            }'
        ]);
        return view('fullcalender', compact(['calendar', 'categoriesArray']));
    }


    public function store(Request $request)
    {
        Input::merge(['start_date' => Carbon::parse($request->start_date . $request->start_time)->toDateTimeString()]);
        Input::merge(['end_date' => Carbon::parse($request->end_date . $request->end_time)->toDateTimeString()]);

        $validator = Validator::make($request->all(), [
            'event_name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
        	\Session::flash('warnning','Please enter the valid details');
            return Redirect::to('/events')->withInput()->withErrors($validator);
        }

        $event = new Event;
        $event->title = $request['event_name'];
        $event->start_date = $request['start_date'];
        $event->end_date = $request['end_date'];
        $event->category_id = $request['category_id'];
        $event->save();

        \Session::flash('success','Event added successfully.');
        return Redirect::to('/events');
    }

    public function show($id){
        $event = Event::findOrFail($id);
        $categoriesArray =  Auth::user()->categories()->get()->mapWithKeys(function ($item) {
            return [$item['id'] => [
                'title' => $item->title,
                'color' => $item->color
            ]];
        })->toArray();
        $start_date = Carbon::parse($event->start_date);
        $end_date = Carbon::parse($event->end_date);
        $event->start_time = $start_date->format('H:i');
        $event->end_time = $end_date->format('H:i');
        $event->start_date = $start_date->toDateString();
        $event->end_date = $end_date->toDateString();

        return view('event', compact(['event', 'categoriesArray']));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Input::merge(['start_date' => Carbon::parse($request->start_date . $request->start_time)->toDateTimeString()]);
        Input::merge(['end_date' => Carbon::parse($request->end_date . $request->end_time)->toDateTimeString()]);

        $validator = Validator::make($request->all(), [
            'event_name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
        	\Session::flash('warnning','Please enter the valid details');
            return Redirect::to('/events/'.$id)->withInput()->withErrors($validator);
        }

        $event = Event::findOrFail($id);
        $event->title = $request['event_name'];
        $event->start_date = $request['start_date'];
        $event->end_date = $request['end_date'];
        $event->category_id = $request['category_id'];
        $event->save();

        \Session::flash('success','Event updated successfully.');
        return Redirect::to('/events/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // delete
        $event = Event::findOrFail($id);
        $event->delete();

        // redirect
        \Session::flash('success', 'Event successfully deleted');
        return Redirect::to('/events');
    }
}