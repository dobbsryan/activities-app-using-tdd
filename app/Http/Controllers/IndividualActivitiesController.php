<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndividualActivitiesController extends Controller
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

    public function index()
    {
        // may want to default to current date but for now,
        // copied in code that sets session variable
        // (2017/12/27)
        $date = session('userSelectedDate');
        if (!$date) {
            $date = Carbon::now('America/Denver');
        }
        $dateAsString = $date->format('Y-m-d');

        $activities = Activity::get();
        $residents = Resident::where('location_id', session('location_id'))
                             ->get();

        //dd($residents);

        $sessionLocationName = session('location_name');
        return view('individual-activities.index')
                        ->with('date', $dateAsString)
                        ->with('locationName', $sessionLocationName)
                        ->with(compact('activities', 'residents'));   
    }
}
