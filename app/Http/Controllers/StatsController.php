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
       $lava = $this->generateChart();

        return view('stats', compact('lava'));
    }

    private function generateChart(){
        $reasons = \Lava::DataTable();

        $reasons->addStringColumn('Reasons')
                ->addNumberColumn('Percent');

        $categories = $this->getAuthUserEvents();

        $this->addToChart($categories, $reasons);

        return $this->createPieChart('Time spend on actvities', $reasons);
    }

    private function getAuthUserEvents(Carbon $filterCreatedAtDate = null, String $filterOperator = null){
        if($date != null && $filterOperator != null){
            return Auth::user()->events()->whereDate('start_date', $filterOperator, $filterCreatedAtDate)->get();
        }
        return Auth::user()->events()->get();
    }

    private function addToChart($categories, &$reasons){
        $categories->groupBy('category.title')->each(function($events, $categoryTitle) use ($reasons){
            $sum = 0;
            $events->each(function($event) use (&$sum){
                $sum += $this->countHoursOfEvent($event);
            });
            $reasons->addRow([$categoryTitle, $sum]);
        });
    }

    private function createPieChart($title , $reasons){
        return \Lava::PieChart('chart', $reasons, [
            'title'  => $title,
            'is3D'   => true,
            'slices' => [
                ['offset' => 0.2],
                ['offset' => 0.25],
                ['offset' => 0.3]
            ]
        ]);
    }

    private function countHoursOfEvent(Event $event){
        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);
        return $endDate->diffInHours($startDate);
    }
}
