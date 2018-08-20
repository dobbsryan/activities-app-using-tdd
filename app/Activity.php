<?php

namespace App;

use App\Domain;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    // protected $appends = ['domainName'];

    public function scheduledActivities()
    {
        return $this->hasMany(ScheduledActivity::class);
    }

    public function isScheduledFor($date)
    {
        return $this->scheduledActivities()->create([
            'date' => $date,
        ]);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    // public function getDomainNameAttribute()
    // {
    //     $domain = $this->domain()->first();
    //     return $domain->name;
    // }
}
