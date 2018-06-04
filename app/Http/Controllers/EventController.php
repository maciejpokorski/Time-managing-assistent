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
    }

    public function index()
    {
        $events = [];
        $categories = Auth::user()->categories()->with('events')->get();
        $categoriesArray = [];
        if($categories->count()) {
            foreach ($categories as $category) {
                $categoriesArray[$category->id] = $category->title;
                foreach ($category->events as $event){
                    $events[] = Calendar::event(
                        $event->title,
                        false,
                        new \DateTime($event->start_date),
                        new \DateTime($event->end_date.'+ 1 day'),
                        null,
                        // Add color and link on event
                        [
                            'color' => $category->color,
                            'url' => 'events/'.$event->id
                        ]
                    );
                }
                
            }
        }

        $calendar = Calendar::addEvents($events);
        return view('fullcalender', compact(['calendar', 'categoriesArray']));
    }

    public function store(Request $request)
    {
        Input::merge(['start_date' => Carbon::parse($request->start_date . $request->start_time)->toDateTimeString()]);
        Input::merge(['end_date' => Carbon::parse($request->end_date . $request->end_time)->toDateTimeString()]);

        $validator = Validator::make($request->all(), [
            'event_name' => 'required|max:255',
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
        $event->user_id = Auth::id();
        $event->save();

        \Session::flash('success','Event added successfully.');
        return Redirect::to('/events');
    }

    public function show($id){
        $event = Event::findOrFail($id);
        $categoriesArray =  Auth::user()->categories()->get()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['title']];
        });
        $start_date = Carbon::parse($event->start_date);
        $end_date = Carbon::parse($event->end_date);
        $event->start_time = $start_date->format('H:i');
        $event->end_time = $end_date->format('H:i');
        $event->start_date = $start_date->toDateString();
        $event->end_date = $end_date->toDateString();

        return view('event', compact(['events', 'categoriesArray']));
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