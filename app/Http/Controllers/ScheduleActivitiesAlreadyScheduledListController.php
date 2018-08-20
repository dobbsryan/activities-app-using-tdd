<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\ScheduledActivity;
use Illuminate\Http\Request;

class ScheduleActivitiesAlreadyScheduledListController extends Controller
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
    
    // NOTE: pulled straight from ScheduleActivitiesController which returns
    // a view. This time I just want it to return the list of already
    // scheduled activities (if they exist) as a result of the
    // Vue frontend component date input having an updated
    // date selected by the user. This is basically an
    // API call. Only wanting to his backend for 
    // data. Don't want page refresh.

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($dateStringFromFrontEnd)
    {
        // set session variable that will be referenced for page refreshes by user
        $date = new Carbon($dateStringFromFrontEnd);
        session(['userSelectedDate' => $date]);

        // get all activites
        // $activities = Activity::get();

        // currently manually creating of times array
        $timesAndScheduledActivities = [
                    ['date' => "", 'time' => "9:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "9:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "10:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "10:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "11:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "11:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "12:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "12:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "1:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "1:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "2:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "2:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "3:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "3:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "4:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "4:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "5:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "5:30", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "6:00", 'activity' => "", 'activity_id' => ""],
                    ['date' => "", 'time' => "6:30", 'activity' => "", 'activity_id' => ""],
                ];

        // get scheduled times for default date
        // currently that date will be "today"
        // $date = Carbon::now('America/Denver');

        // for api call, we want date to be whatever was passed in
        // TODO [ ] will deal with timezone issue on refactor
        //$date = new Carbon($dateStringFromFrontEnd); // set above for session variable use
        $dateAsString = $date->format('Y-m-d');
        $scheduledActivities = ScheduledActivity::with('activity')
                                         ->whereYear('date', $date->year)
                                         ->whereMonth('date', $date->month)
                                         ->whereDay('date', $date->day)
                                         ->where('location_id', session('location_id'))
                                         ->get();

        // build the array for already scheduled activities according to the
        // $timesAndScheduledActivities array slots
        $ctr = 0;
        foreach ( $timesAndScheduledActivities as $timeAndScheduledActivity ) {
            foreach ( $scheduledActivities as $scheduledActivity ) {
                
                // get the time in hour 1-12 format
                $dt = new Carbon($scheduledActivity->date);
                $scheduledTime = $dt->format('g:i');
                $scheduledDate = $dt->format('Y-m-d');

                // match up with time array and drop activity into position
                if ($timeAndScheduledActivity['time'] == $scheduledTime) {
                    $timesAndScheduledActivities[$ctr]['activity'] = $scheduledActivity->activity->name;
                    $timesAndScheduledActivities[$ctr]['activity_id'] = $scheduledActivity->activity_id; // yes, activity id
                    
                    $timesAndScheduledActivities[$ctr]['date'] = $scheduledDate;
                    // $timesAndScheduledActivities[$ctr]['date'] = $scheduledActivity->date;
                    
                    //$timesAndScheduledActivities[$ctr]['activity_id'] = $scheduledActivity->id;
                    //echo $scheduledActivity->activity->name . ' and id: ' . $scheduledActivity->activity_id;
                }

            }
            $ctr++;
        }

        $timesAndScheduledActivities = collect($timesAndScheduledActivities);
        // added collect() because I don't know why you can't compact an array in the form I had it;
        // needed a parent level key maybe?
        // collect makes the array an object and Laravel can handle passing that. Geez.
        return compact('timesAndScheduledActivities');
        // return view('schedule-activities.index')
        //                 ->with('date', $dateAsString)
        //                 ->with(compact('activities'))
        //                 ->with(compact('timesAndScheduledActivities'));
    }
}
