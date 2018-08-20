<?php

namespace App\Http\Controllers;

// use App\ScheduledActivity;
use App\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceRecordsController extends Controller
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
    
    public function show(Request $request)
    {
        // get the request data into vars
        $activeActivityDetails = $request->activeActivityDetails;
        //return $activeActivityDetails;
        $activity = $activeActivityDetails['activity']; // dont need but have access to
        $activityId = $activeActivityDetails['activity_id'];
        $date = $activeActivityDetails['date'];
        $time = $activeActivityDetails['time'];

        // to get the scheduled activity id, we need the activity id and date
        $scheduledActivity = DB::table('scheduled_activities')
                ->where('activity_id', $activityId)
                ->where('date', $date . ' ' . $time)
                ->first();

        // return $scheduledActivity->id;
        $scheduledActivityId = $scheduledActivity->id;

        // $scheduledActivity = ScheduledActivity::find($scheduledActivityId);
        // $residents = Resident::all();
        
        // // correctly retrieving all records for all residents in table
        // // having scheduled group activity
        // $residents = Resident::with(['attendanceRecords' => function ($query) use ($scheduledActivityId) {
        //     $query->where('scheduled_activity_id', $scheduledActivityId);
        // }])->get();

        // limiting records to residents based on user selected location for
        // residents in table having scheduled group activity
        $residents = Resident::with(
                                    ['attendanceRecords' => function ($query) use ($scheduledActivityId) {
                                        $query->where('scheduled_activity_id', $scheduledActivityId);
                                    }])
                             ->where('location_id', session('location_id'))
                             ->get();

        // return view('scheduledActivities.show', compact(['scheduledActivity', 'residents']));
        return ['scheduledActivityId' => $scheduledActivityId, 'residents' => $residents];
    }
}
