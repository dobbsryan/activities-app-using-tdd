<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\IndividualAttendanceRecord;

class IndividualAttendanceRecordsController extends Controller
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
        // set the session date;
        // will either already be set as this date; :. redundant;
        // or will have been changed by user and should now be reset
        $date = new Carbon($request->date);
        session(['userSelectedDate' => $date]);

        // // trying to limit list of residents to only those
        // // with the user selected location_id
        // // use date from request to find the attendance records
        // $locationId = session('location_id');
        // $records = IndividualAttendanceRecord::with([
        //                                         'resident' => function ($query) use ($locationId) {
        //                                             $query->where('location_id', $locationId);
        //                                             // $query->where('location_id', session('location_id'));
        //                                         },
        //                                         'activity'
        //                                     ])
        //                  ->where('date', $date)
        //                  ->get();

        // works for grabbing entire list of residents
        // use date from request to find the attendance records
        $records = IndividualAttendanceRecord::with(['resident', 'activity'])
                         ->where('date', $date)
                         ->where('location_id', session('location_id'))
                         ->get();

        // starting point
        //$records = IndividualAttendanceRecord::get()->where('date', $request->date);
        //dd($records);

        // return response()->json([
        //     'name' => 'Abigail',
        //     'state' => 'CA'
        // ]);
        return response()->json($records);
    }

    public function store(Request $request)
    {
        $individualAttendanceRecord = IndividualAttendanceRecord::create([
            'resident_id' => request('resident_id'),
            'activity_id' => request('activity_id'),
            'date' => request('date'),
            'location_id' => session('location_id'),
        ]);

        //return ['id' => $individualAttendanceRecord->id];
        // return response()->json([
        //     'name' => 'Abigail',
        //     'state' => 'CA'
        // ]);
        return response()->json($individualAttendanceRecord);
    }

    public function destroy(IndividualAttendanceRecord $individualAttendanceRecord)
    {
        $individualAttendanceRecord->delete();
    }
}
