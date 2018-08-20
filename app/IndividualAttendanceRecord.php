<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndividualAttendanceRecord extends Model
{
    protected $guarded = [];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
