<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use App\Http\Requests\PickupPoint\StorePickupPointRequest;
use App\Http\Requests\PickupPoint\UpdatePickupPointRequest;
use App\Models\PickupPoint;
use App\Models\User;
use Illuminate\Http\Request;

class PickupPointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pickup_points'] = PickupPoint::with('user')->latest()->paginate(20);
        return view('backend.shipping.pickup.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['staffs'] = User::where('user_type','staff')->get();
        return view('backend.shipping.pickup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePickupPointRequest $request)
    {
        $data = PickupPoint::create($request->validated());
        flash(translate('Data has been inserted successfully'))->success();
        return redirect()->route('pickup-point.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PickupPoint $id)
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
        $data['pickup_point'] = PickupPoint::find($id);;
        $data['staffs'] = User::where('user_type','staff')->get();
        return view('backend.shipping.pickup.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePickupPointRequest $request, $id)
    {
        $pickup_point = PickupPoint::find($id);
        $pickup_point->update($request->validated());
        flash(translate('Data has been inserted successfully'))->success();
        return redirect()->route('pickup-point.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $pickup_point = PickupPoint::find($id);
        $pickup_point->delete();
        flash(translate('Data has been deleted successfully'))->success();
        return redirect()->route('pickup-point.index');
    }
}
