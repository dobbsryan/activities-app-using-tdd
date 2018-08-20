<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledActivity extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];

    //protected $appends = ['activityName'];

    public function getFormattedDateTimeAttribute()
    {
        return $this->date->format('F j, Y g:ia');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);   
    }

    public function resident()
    {
        return $this->belongsToMany(Resident::class,
                                    'attendance_records',
                                    'scheduled_activity_id',
                                    'resident_id');     
    }

    // public function getActivityNameAttribute()
    // {
    //     $activity = $this->activity()->first();
    //     return $activity->name;
    // }
}

