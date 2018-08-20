<?php

namespace App\Http\Controllers;

use App\Resident;
use Carbon\Carbon;
use App\ScheduledActivity;
use Illuminate\Http\Request;

class ScheduledActivitiesController extends Controller
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
    
    // NOTE: this is code for attendance taking view only; code structure
    // is being copied from ScheduleActivitiesController
    // because the layouts are similar (2017-12-10)
    public function index()
    {
        // get the date session variable if exists, meaning that user
        // is expecting to see this date upon refreshing the view
        $date = session('userSelectedDate');
        
        if (!$date) {
            $date = Carbon::now('America/Denver');
        }

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

        // get scheduled times for date
        $dateAsString = $date->format('Y-m-d');
        $scheduledActivities = ScheduledActivity::with('activity')
                                         ->whereYear('date', $date->year)
                                         ->whereMonth('date', $date->month)
                                         ->whereDay('date', $date->day)
                                         ->where('location_id', session('location_id'))
                                         ->get();

        // foreach ( $scheduledActivities as $scheduledActivity ) {
        //     dd($scheduledActivity->activity->name);   
        // }
        //dd($scheduledActivities);

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

        // defaulting to gathering the resident attendance for the first activity scheduled;
        // this will be shown on initial load as the activity for which attendance
        // can be taken
        $ctr = 0;
        $activityId = '';
        $date = '';
        foreach ( $timesAndScheduledActivities as $timeAndScheduledActivity ) {
            if ($timesAndScheduledActivities[$ctr]['activity_id']) {
                $activityId = $timesAndScheduledActivities[$ctr]['activity_id'];
                $date = $timesAndScheduledActivities[$ctr]['date'];
                //echo $activityId; // for this example is id of 11 for "new name" activity
                break;
            }
            $ctr++;
        }

        // now with both the activity_id and date, can get the scheduled_activities id;
        // can then get the attendance record
        //$attendanceRecord = AttendanceRecord::where('resident_id', )

        // $residents = Resident::with(['attendanceRecords' => function ($query) use ($activityId) {
        //     $query->where('scheduled_activity_id', $scheduledActivityId);
        // }])->get();


        $timesAndScheduledActivities = collect($timesAndScheduledActivities);
        // dd($timeAndScheduledActivity);
        // added collect() because I don't know why you can't compact an array in the form I had it;
        // needed a parent level key maybe?
        // collect makes the array an object and Laravel can handle passing that. Geez.
        $sessionLocationName = session('location_name');
        //dd($sessionLocationName);
        return view('scheduled-activities.index')
                        ->with('date', $dateAsString)
                        ->with('locationName', $sessionLocationName)
                        //->with(compact('residents'))
                        ->with(compact('timesAndScheduledActivities'));
    }

    // // original
    // // was fine for listing one activity for viewing and taking its attendance
    // public function show($id)
    // {
    //     $scheduledActivity = ScheduledActivity::find($id);
    //     //$residents = Resident::all();
    //     $residents = Resident::with(['attendanceRecords' => function ($query) use ($id) {
    //         $query->where('scheduled_activity_id', $id);
    //     }])->get();
    //     return view('scheduledActivities.show', compact(['scheduledActivity', 'residents']));
    // }

    public function store(Resident $resident, ScheduledActivity $scheduledActivity)
    {
        $resident->attends($scheduledActivity);
    }

    public function destroy(Resident $resident, ScheduledActivity $scheduledActivity)
    {
        $resident->attends($scheduledActivity);
    }
}
