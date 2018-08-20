<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $guarded = [];
    
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}

