<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get the last selected user location off of the user model
        $user = Auth::user();
        $userLastSelectedLocationId = $user->last_selected_location_id;
        
        // get the list of locations; will pass them into the view
        $locations = Location::get();
        
        //-------------------------
        // RIDICULOUS - should be better way than that mess below; 
        // wanted to use collection and not run another query 
        // but GEEZ!!! (2017-01-09)
        
        // from list of locations, set the session variables for
        // the location for last selected location of user
        $filtered = $locations->filter(function($location) use ($userLastSelectedLocationId) {
            return $location->id == $userLastSelectedLocationId;
        });
        // dd($filtered);
        $asArray = $filtered->values('id')->toArray();

        // set session
        session([
            'location_id' => $asArray[0]['id'],
            'location_name' => $asArray[0]['name'],
        ]);
        //dd($asArray[0]['name']);
        // $location_id = session('location_id', $asArray[0]['id']);
        // $location_name = session('location_name', $asArray[0]['name']);
        $location_id = $asArray[0]['id'];
        $location_name = $asArray[0]['name'];
        //-------------------------

        return view('home', [
            'locations' => $locations,
            'location_id' => $location_id,
            'location_name' => $location_name,
        ]);
    }

    public function store(Location $location)
    {
        // set session
        session([
            'location_id' => $location->id,
            'location_name' => $location->name,
        ]);

        $location_id = session('location_id');
        $location_name = session('location_name');

        // update user last_selected_location_id record
        $user = Auth::user();
        $user->update([
            'last_selected_location_id' => $location_id,
        ]);

        // return to front end (just to confirm)
        return response()->json([
            'location_id' => $location_id,
            'location_name' => $location_name
        ]);
    }
}
