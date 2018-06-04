<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use App\Event;
use Carbon\Carbon;
use Auth;

class StatsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reasons = \Lava::DataTable();

        $reasons->addStringColumn('Reasons')
                ->addNumberColumn('Percent');

        $categories = Auth::user()->categories()->with('events')->get();

        $data = [];
        foreach($categories as $category){
            $data[$category->title]['count_hours'] = 0;
                foreach($category->events as $event){
                    $data[$category->title]['count_hours'] += $this->countHoursOfEvent($event);
                }
            $reasons->addRow([$category->title, $data[$category->title]['count_hours']]);
        }

       $lava = \Lava::PieChart('IMDB', $reasons, [
            'title'  => 'Time spend on activities',
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ]);
        return view('stats', compact('lava'));
    }

    private function countHoursOfEvent(Event $event){
        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);
        return $endDate->diffInHours($startDate);
    }
}
