<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at']; // for soft deletes

    //protected $with = ['attendanceRecords']; // eager loads all
    
    public function attends($scheduledActivity)
    {
        // should toggle the attendance_records table
        $this->attendanceRecords()->toggle($scheduledActivity);
    }

    /*
     * attendanceRecords that belong to the resident
     */
    public function attendanceRecords()
    {
        // actually not quite sure how this is working since
        // i'm listing the id columns on the intermediate
        // and yet calling on the ScheduledActivity
        // model;
        // For now, just know that this is what
        // is working for the scheduling of
        // group activities (2017-01-04)
        return $this->belongsToMany(ScheduledActivity::class,
                                    'attendance_records',
                                    'resident_id',
                                    'scheduled_activity_id');
    }

    // NOTE: currently only using this in a test (2017-11-25)
    // may want to delete
    public function isAttending($scheduledActivity)
    {
        return DB::table('attendance_records')
                ->where('resident_id', $this->id)
                ->where('scheduled_activity_id', $scheduledActivity->id)
                ->exists();
    }

    public function scheduledActivities()
    {
        // for this methods, going directly at the ScheduledActivity
        // model for the Reports view. I'm needing to get all the
        // group activities for the resident for the entire
        // month;
        // belongs to many through the attendance_records table
        return $this->belongsToMany(ScheduledActivity::class, 'attendance_records');
    }
}

