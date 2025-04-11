<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Membership;

class MembershipController extends Controller
{
    public function index() {
        $memberships = Membership::orderBy('id', 'DESC')->paginate(10);
        return view('backend.membership.index', compact('memberships'));
    }

    public function create() {
        return view('backend.membership.create_and_edit');
    }

    public function store(Request $request) {
        $membership = new Membership;
        $membership->name = $request->title;
        $membership->description = $request->description;
        $membership->percent = $request->percent;
        $membership->value = $request->amount;
        $membership->save();
        return redirect(route('membership.index'));
    }

    public function edit($id) {
        $membership = Membership::find($id);
        return view('backend.membership.create_and_edit', compact('membership'));
    }

    public function update(Request $request, $id) {
        $membership = Membership::find($id);
        $membership->name = $request->title;
        $membership->description = $request->description;
        $membership->percent = $request->percent;
        $membership->value = $request->amount;
        $membership->save();
        return redirect(route('membership.index'));
    }

    public function destroy($id) {
        $membership = Membership::find($id);
        $membership->delete();
        return redirect(route('membership.index'));
    }
}