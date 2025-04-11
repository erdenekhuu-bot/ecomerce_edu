<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_country = $request->sort_country;
        $sort_state = $request->sort_state;

        $state_queries = State::query();
        if ($request->sort_state) {
            $state_queries->where('name', 'like', "%$sort_state%");
        }
        if ($request->sort_country) {
            $state_queries->where('country_id', $request->sort_country);
        }

        $states = $state_queries->paginate(15);
        return view('backend.settings.states.index', compact('states', 'sort_country', 'sort_state'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $state = new State;

        $state->name        = $request->name;
        $state->country_id  = $request->country_id;

        $state->save();

        flash(translate('State has been inserted successfully'))->success();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state  = State::findOrFail($id);
        $countries = Country::where('status', 1)->get();

        return view('backend.settings.states.edit', compact('countries', 'state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $state = State::findOrFail($id);

        $state->name        = $request->name;
        $state->country_id  = $request->country_id;

        $state->save();

        flash(translate('State has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request)
    {

        DB::transaction(function() use ($request){
            $state = State::with('cities')->where('id',$request->id)->first();
            $state->status = $request->status;
            $state->save();

            $state->cities()->update([ 'status' => $request->status ]);
        });

        return 1;
    }

    public function getStates(Request $request)
    {
        $countries = Country::with('states','states.cities')->whereIn('id', $request->selectedCountriesIds)->get();
        $states = State::where('status', 1)->where('country_id', $request->country_id)->get();
        $html=null;
        // $html = '<option value="">' . translate("Select State") . '</option>';
        foreach ($countries as $country) {
            foreach($country->states as $state){
                $html .= '<option value="' . $state->id . '">' . $state->name . '</option>';
            }
        }

        return json_encode($html);
    }
}
