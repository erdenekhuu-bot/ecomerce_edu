<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $zones = Zone::with('cities')->paginate(15);
        return view('backend.settings.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['countries'] = Country::where('status', 1)->get();
        $data['states'] = State::where('status', 1)->get();
        $data['cities'] = City::where('status', 1)->where('zone_id', null)->get();
        return view('backend.settings.zones.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zone = new Zone;
        $zone->name = $request->name;
        $zone->standard_delivery_cost = $request->standard_delivery_cost ?? 0;
        $zone->express_delivery_cost = $request->express_delivery_cost ?? 0;
        $zone->cities = $request->states ? json_encode($request->states) : '[]';
        $zone->save();

        State::whereIn('id', $request->states)->update(['zone_id'=>$zone->id]);
        
        flash(translate('Zone has been inserted successfully'))->success();
        return redirect()->route('zones.index');
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
        $zone = Zone::findOrFail($id);
        // Retrieve cities with their respective states
        $cities = City::with(['state', 'state.country'])->whereIn('id', json_decode($zone->cities))->get();

        // Collect unique states
        $selectedStates = $cities->pluck('state')->unique('id');
        $selectedCountries = $selectedStates->pluck('country')->unique('id');
        $data['selectedCountries'] = $selectedCountries->pluck('id');
        $data['selectedStates'] = $selectedStates->pluck('id');
        $data['countries'] = Country::where('status', 1)->get();
        $data['states'] = State::where('status', 1)->get();
        
        $data['zone'] = Zone::findOrFail($id);
        $data['cities'] = $cities;
        

        return view('backend.settings.zones.edit', $data);
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
        $zone = Zone::findOrFail($id);
        $zone->name = $request->name;
        $zone->cities = $request->states ? json_encode($request->states) : '[]';
        $zone->standard_delivery_cost = $request->standard_delivery_cost ?? 0;
        $zone->express_delivery_cost = $request->express_delivery_cost ?? 0;

        State::whereIn('id', $request->states)->update(['zone_id'=>$zone->id]);
        $zone->save();

        flash(translate('Zone has been updated successfully'))->success();
        return redirect()->route('zones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->cities()->update(['zone_id'=>null]);
        $zone->delete();

        flash(translate('Zone has been deleted successfully'))->success();
        return redirect()->route('zones.index');
    }

    public function updateStatus(Request $request)
    {
        $zone = Zone::findOrFail($request->id);
        $zone->status = $request->status;
        if ($zone->save()) {
            return 1;
        }
        return 0;
    }
}
