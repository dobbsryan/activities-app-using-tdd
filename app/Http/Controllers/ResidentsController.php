<?php

namespace App\Http\Controllers;

use App\Resident;
use Illuminate\Http\Request;

class ResidentsController extends Controller
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
        $residents = Resident::where('location_id', session('location_id'))->get();
        $sessionLocationName = session('location_name');
        return view('residents.index', [
            'residents' => $residents,
            'locationName' => $sessionLocationName,
        ]);
    }

    public function destroy(Resident $resident)
    {
        // either works
        //Resident::destroy($resident->id);
        $resident->delete();
    }

    public function store(Request $request)
    {
        $resident = Resident::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'location_id' => session('location_id'),
        ]);

        return ['id' => $resident->id];
    }

    public function update(Resident $resident)
    {
        // not sure this is the best approach but got my test to green (2017-11-27)
        $resident->update([
            'first_name' => request('first_name'),
        ]);
        $resident->update([
            'last_name' => request('last_name'),
        ]);
    }
}
