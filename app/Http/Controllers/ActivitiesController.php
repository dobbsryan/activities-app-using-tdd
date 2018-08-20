<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Activity;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
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
        // adding domains to view
        $activities = Activity::with('domain:id,name')->get();

        // want to pass the list of domains as well
        // for select dropdown for domain col
        $domains = Domain::get();

        return view('activities.index', ['activities' => $activities, 'domains' => $domains]);

        // original: before considering domains
        // $activities = Activity::get();
        // return view('activities.index', ['activities' => $activities]);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
    }

    public function store(Request $request)
    {
        $activity = Activity::create([
            'name' => request('name'),
            'domain_id' => request('domain_id'),
            'description' => request('description'),
            'supplies' => request('supplies'),
            'instructions' => request('instructions'),
        ]);

        return ['id' => $activity->id];
    }

    public function update(Activity $activity)
    {
        // not sure this is the best approach but got my test to green (2017-11-27)
        $activity->update([
            'name' => request('name'),
        ]);
        $activity->update([
            'domain_id' => request('domain_id'),
        ]);
        $activity->update([
            'description' => request('description'),
        ]);
        $activity->update([
            'supplies' => request('supplies'),
        ]);
        $activity->update([
            'instructions' => request('instructions'),
        ]);
    }
}
