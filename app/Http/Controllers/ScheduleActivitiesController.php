<?php

namespace App\Http\Controllers;

use App\Activity;
use Carbon\Carbon;
use App\ScheduledActivity;
use Illuminate\Http\Request;

class ScheduleActivitiesController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get the date session variable if exists, meaning that user
        // is expecting to see this date upon refreshing the view
        $date = session('userSelectedDate');
        if (!$date) {
            $date = Carbon::now('America/Denver');
        }

        // get all activites
        $activities = Activity::get();

        // currently manually creating of times array
        $timesAndScheduledActivities = [
                    ['time' => "9:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "9:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "10:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "10:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "11:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "11:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "12:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "12:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "1:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "1:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "2:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "2:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "3:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "3:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "4:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "4:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "5:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "5:30", 'activity' => "", 'activity_id' => ""],
                    ['time' => "6:00", 'activity' => "", 'activity_id' => ""],
                    ['time' => "6:30", 'activity' => "", 'activity_id' => ""],
                ];

        // get scheduled times for default date
        // currently that date will be "today"
        //$date = Carbon::now('America/Denver'); // checking if exists on session above
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
        $sessionLocationName = session('location_name');
        return view('schedule-activities.index')
                        ->with('date', $dateAsString)
                        ->with('locationName', $sessionLocationName)
                        ->with(compact('activities'))
                        ->with(compact('timesAndScheduledActivities'));
    }

    public function store($frontEndDate, Request $request)
    {
        $date = $request['date'];
        $time = $request['time'];
        $c = new Carbon($date . $time);
        
        ScheduledActivity::create([
                                'date' => $c,
                                'activity_id' => $request['activity_id'],
                                'location_id' => session('location_id'),
                            ]);
    }

    // public function update(Request $request) // request is implicit
    public function update($frontEndDate, Request $request)
    {
        // take string date passed in from front end and make it an object
        $frontEndDateAsObject = new Carbon($frontEndDate);

        // get all the scheduled activities for that date if they exist
        $scheduledActivities = ScheduledActivity::with('activity')
                                         ->whereYear('date', $frontEndDateAsObject->year)
                                         ->whereMonth('date', $frontEndDateAsObject->month)
                                         ->whereDay('date', $frontEndDateAsObject->day)
                                         ->get();

        // for all "currently" scheduled activities for date
        foreach ($scheduledActivities as $scheduledActivity) {
            
            //echo $scheduledActivity;
            // grab the id for comparison
            $dbTableId = $scheduledActivity->activity_id;
            //echo $dbTableId . ' - ';
            
            // for each item of the front end items passed thru
            foreach ($request->sortedScheduleActivities as $sortedActivityData) {

                if ($sortedActivityData['name'] != '' && $sortedActivityData['activity_id'] == $dbTableId) {

                    $date = $sortedActivityData['date'];
                    $time = $sortedActivityData['time'];
                    $c = new Carbon($date . $time);
                    
                    echo $sortedActivityData['name'] . ': ' . $c . ': ids: ' . $sortedActivityData['activity_id'] . ' ----- ';

                    $scheduledActivity->date = $c;
                    $scheduledActivity->update();
                    break;
                }
            }
        }
        return response('Update Successful.', 200);
    }

    public function destroy($frontEndDate, Request $request)
    {
        
        $date = $request['date'];
        $time = $request['time'];
        $c = new Carbon($date . $time);
        
        $scheduledActivities = ScheduledActivity::with('activity')
                                            ->where('date', $c)
                                            ->where('activity_id', $request['activity_id'])
                                            ->delete();
        // // delete record with the date/time and activity_id
        // // take string date passed in from front end and make it an object
        // $frontEndDateAsObject = new Carbon($frontEndDate);

        // // get all the scheduled activities for that date if they exist
        // $scheduledActivities = ScheduledActivity::with('activity')
        //                                  ->whereYear('date', $frontEndDateAsObject->year)
        //                                  ->whereMonth('date', $frontEndDateAsObject->month)
        //                                  ->whereDay('date', $frontEndDateAsObject->day)
        //                                  ->where()
        //                                  ->get();


        return response('Update Successful.', 200);

        // $scheduledActivity = ScheduledActivity::findOrFail($id);
        // $scheduledActivity->delete();
        // return response('Deletion Successful.', 200);

        // $scheduledActivity = ScheduledActivity::findOrFail($id);
        // try {
        //     $scheduledActivity->delete();
        //     return response('Deletion Successful.', 200);
        //     // \Session::flash('alert-success', 'scheduledActivity eliminada correctamente!');
        // } catch (Illuminate\Database\QueryException $e) {
        //     return response('Cannot delete an activity for which attendance has already been taken.', 500);
        //     // \Session::flash('alert-danger', 'No puedes eliminar scheduledActivity relacionada con empleado, elimina primero el empleado!');
        // }
        //return redirect()->route('scheduledActivitys.index');
    }
}
