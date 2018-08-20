<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Comment;
use App\Resident;
use Carbon\Carbon;
use App\ScheduledActivity;
use Illuminate\Http\Request;
use App\IndividualAttendanceRecord;

class ReportsController extends Controller
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
        $date = session('userSelectedDate');
        if (!$date) {
            $date = Carbon::now('America/Denver');
        }
        $dateAsString = $date->format('Y-m');

        $residents = Resident::where('location_id', session('location_id'))
                             ->get();

        // pass user name too for "completed by:" at bottom of report
        
        // pass session location name
        $sessionLocationName = session('location_name');
        
        return view('reports.index')
                        ->with('date', $dateAsString)
                        ->with('locationName', $sessionLocationName)
                        ->with(compact('residents'));   
                        // ->with(compact('residents', 'domains'));   
    }

    public function show(Request $request)
    {
        // request from frontend has resident_id and month;
        $residentId = $request->resident_id;
        $month = $request->date; // note passing in "date" var not "month" var BUT value represented as "2018-01"

        // setting date range for month
        $monthBeg = Carbon::parse($month)->startOfMonth();
        $monthEnd = Carbon::parse($month)->endOfMonth();
        //dd(compact('monthBeg', 'monthEnd')); // Carbon library fucking rocks!!!
        
        // retrieve group activities attended for month for resident
        // and pass them to back frontend
        $residentGroupAttendance = Resident::find($residentId)
                                ->scheduledActivities()
                                ->whereBetween('date', [$monthBeg, $monthEnd])
                                ->with('activity.domain')
                                ->get();

        // dd($residentGroupAttendance);
        //  ------------------  

        // making unique
        // no repeating activities sent to front end
        // and adding as comma separate list
        $residentGroupAttendance_UniqueDomainReference = $residentGroupAttendance->unique('activity.domain')->values()->all();

        $residentGroupAttendance_UniqueActivitiesReferences = $residentGroupAttendance->unique('activity')->values()->all();
        
        foreach ($residentGroupAttendance_UniqueDomainReference as $key => $domainRecord) {
            
            $activitiesCommaSeparatedList = '';
            
            foreach ($residentGroupAttendance_UniqueActivitiesReferences as $activityRecord) {
                //print_r($record->activity['domain']['name']);
                if ($domainRecord->activity['domain']['name'] == $activityRecord->activity['domain']['name']) {
                    $activitiesCommaSeparatedList = $activitiesCommaSeparatedList . $activityRecord->activity['name'] . ', ';
                }
            }

            // remove last comma
            $activitiesCommaSeparatedList = substr($activitiesCommaSeparatedList, 0, -2); // -2 because of comma and space
            $residentGroupAttendance_UniqueDomainReference[$key]['activity']['name'] = $activitiesCommaSeparatedList;
            // $domainRecord->activity['name'] = $activitiesCommaSeparatedList;            
        }
        // print_r($residentGroupAttendance);
        // die();

        // foreach ($residentGroupAttendance as $record) {
        //     print_r($record->activity['name']);
        //     print_r($record->activity['domain']['name']);
        // }
        //die();

        //  ------------------ 

        // adding individual record retrieval...
        // retrieving individual activities attended for month for resident
        // and passing them to back frontend
        $residentIndividualAttendance = IndividualAttendanceRecord::where('resident_id', $residentId)
                                            ->whereBetween('date', [$monthBeg, $monthEnd])
                                            // ->orWhere('date', $monthBeg)
                                            // ->orWhere('date', $monthEnd)
                                            ->with('activity.domain')
                                            ->get();
                                            //->toSql();
        // dd($residentIndividualAttendance);

        //  ------------------  
        
        // making unique
        // no repeating activities sent to front end
        //$residentIndividualAttendance = $residentIndividualAttendance->unique();

        // making unique
        // no repeating activities sent to front end
        // and adding as comma separate list
        $residentIndividualAttendance_UniqueDomainReference = $residentIndividualAttendance->unique('activity.domain')->values()->all();

        $residentIndividualAttendance_UniqueActivitiesReferences = $residentIndividualAttendance->unique('activity')->values()->all();
        
        foreach ($residentIndividualAttendance_UniqueDomainReference as $key => $domainRecord) {
            
            $activitiesCommaSeparatedList = '';
            
            foreach ($residentIndividualAttendance_UniqueActivitiesReferences as $activityRecord) {
                //print_r($record->activity['domain']['name']);
                if ($domainRecord->activity['domain']['name'] == $activityRecord->activity['domain']['name']) {
                    $activitiesCommaSeparatedList = $activitiesCommaSeparatedList . $activityRecord->activity['name'] . ', ';
                }
            }

            // remove last comma
            $activitiesCommaSeparatedList = substr($activitiesCommaSeparatedList, 0, -2); // -2 because of comma and space
            $residentIndividualAttendance_UniqueDomainReference[$key]['activity']['name'] = $activitiesCommaSeparatedList;
            // $domainRecord->activity['name'] = $activitiesCommaSeparatedList;            
        }

        //  ------------------ 

        // adding comment retrieval...
        $comment = Comment::where('resident_id', $residentId)
                          ->where('date', $month)
                          ->get()->pluck('comment');

        //  ------------------  

        return response()->json([
                            'group' => $residentGroupAttendance_UniqueDomainReference,
                            // 'group' => $residentGroupAttendance,
                            'individual' => $residentIndividualAttendance_UniqueDomainReference,
                            // 'individual' => $residentIndividualAttendance,
                            'comment' => $comment,
                        ], 201);

    }

    public function store(Request $request)
    {
        // because of nature of single page view for report view,
        // this store action is forced to be smart... as far
        // as I can tell with way I'm currently using
        // Vue and Laravel (2018-01-06)

        // to create and update
        // see if comment already exists
        $commentAlreadyInDb = Comment::where('resident_id', $request->resident_id)
                                     ->where('date', $request->date)
                                     ->first();

        // if comment does not exist and comment passed from user,
        // create the new comment and save it to database
        if ( ! $commentAlreadyInDb && $request->comment != '' ) {

            $newComment = new Comment;
            $newComment->date = $request->date;
            $newComment->resident_id = $request->resident_id;
            $newComment->comment = $request->comment;
            $newComment->save();

            return;

        }

        // if comment exists in database and comment passed from user,
        // update/overwrite comment
        if ($commentAlreadyInDb && $request->comment != '') {
            $commentAlreadyInDb->comment = $request->comment;
            $commentAlreadyInDb->save();
            return;
        }

        // if comment exists in database and no comment is passed from user,
        // delete the comment
        if ($commentAlreadyInDb && $request->comment == '') {
            $commentAlreadyInDb->delete();
            return;
        }
    }
}
