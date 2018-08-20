<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Http\Request;

class DomainsController extends Controller
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
        $domains = Domain::get();

        return view('domains.index', ['domains' => $domains]);
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();   
    }

    public function store(Request $request)
    {
        $domain = Domain::create([
            'name' => request('name'),
        ]);

        return ['id' => $domain->id];
    }

    public function update(Domain $domain)
    {
        //dd($domain);
        $domain->update([
            'name' => request('name'),
            // 'name' => $domain->name,
        ]); 
    }
}
