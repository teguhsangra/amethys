<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\DedicatedPhone;
use App\Models\Location;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;


class DedicatedPhoneController extends Controller
{
    private $url = 'dedicated_phone';
    private $form_id = 'dedicated_phone_form';
    private $table_name = 'dedicated_phones';
    private $prefix_name = 'Dp';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $location_id = \Request::get('location_id');

        $data['location_id'] = $location_id;
        $data['location'] = Location::all();
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        return view('pages.master.dedicated_phones.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        return view('pages.master.dedicated_phones.editor', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'number' => 'required|unique:dedicated_phones',
            'type' => 'required',
            'availability' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $dedicated = new DedicatedPhone();
            $dedicated->location_id = $request['location_id'];
            $dedicated->number = $request['number'];
            $dedicated->type = $request['type'];
            $dedicated->availability = $request['availability'];
            $dedicated->created_by = Auth::user()->name;
            if ($dedicated->save()) {
                \Session::flash('success', 'You are success in inputing your data');
            } else {
                \Session::flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['dedicated'] = DedicatedPhone::findOrFail($id);
        return view('pages.master.dedicated_phones.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['dedicated'] = DedicatedPhone::findOrFail($id);
        $data['locations'] = Auth::user()->location;
        return view('pages.master.dedicated_phones.editor', $data);
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
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $dedicated = DedicatedPhone::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'number' => 'required|unique:dedicated_phones,number,' . $dedicated->id,
            'type' => 'required',
            'availability' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $dedicated->location_id = $request['location_id'];
            $dedicated->number = $request['number'];
            $dedicated->type = $request['type'];
            $dedicated->availability = $request['availability'];
            $dedicated->updated_by = Auth::user()->name;
            if ($dedicated->save()) {
                \Session::flash('success', 'You are success in updating your data');
            } else {
                \Session::flash('error', 'You are failed in updating your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $dedicated = DedicatedPhone::findOrFail($id);
        if ($dedicated->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }
    public function get_by_location_id(Request $request, $id)
    {
        $active_status_id = array(1, 2);
        $not_avail_id = array();

        if(!empty($request['booking_id'])){
            $booking_dedicated_phone = DB::table('booking_dedicated_phones')
                ->leftjoin('bookings', 'booking_dedicated_phones.booking_id', 'bookings.id')
                ->select('booking_dedicated_phones.*')
                ->whereIn('bookings.status_id', $active_status_id)
                ->where('bookings.id', '!=', $request['booking_id'])
                ->get();
        }else{
            $booking_dedicated_phone = DB::table('booking_dedicated_phones')
                ->join('bookings', 'booking_dedicated_phones.booking_id', 'bookings.id')
                ->select('booking_dedicated_phones.*')
                ->whereIn('bookings.status_id', $active_status_id)
                ->get();
        }

        foreach($booking_dedicated_phone as $detail){
            array_push($not_avail_id, $detail->dedicated_phone_id);
        }

        $global_phone = DedicatedPhone::where('location_id', $id)
                            ->where('availability', 'global')
                            ->get();

        $dedicated_phone = DedicatedPhone::where('location_id', $id)
                            ->where('availability', 'dedicated')
                            ->whereNotIn('id', $not_avail_id)
                            ->get();

        $dedicated_phones = $global_phone->merge($dedicated_phone);

        return $dedicated_phones;
    }
    public function get_by_id($id)
    {
        return DedicatedPhone::findOrFail($id);
    }
    public function datatables(Request $request)
    {
        $location_id  = $request['location_id'];

        if ($location_id != '' || $location_id != null) {
            $dedicated = DedicatedPhone::select(
                'dedicated_phones.*',
                DB::raw(
                    '(
                    CASE WHEN dedicated_phones.type = "vo" THEN "Virtual Office"
                    ELSE  "Serviced Office" END) AS type'
                ),
                'locations.name as location'
            )->join('locations', 'locations.id', 'dedicated_phones.location_id')
                ->where('dedicated_phones.location_id', $location_id)
                ->get();
        } else {
            $dedicated = DedicatedPhone::select(
                'dedicated_phones.*',
                DB::raw(
                    '(
                    CASE WHEN dedicated_phones.type = "vo" THEN "Virtual Office"
                    ELSE  "Serviced Office" END) AS type'
                ),
                'locations.name as location'
            )->join('locations', 'locations.id', 'dedicated_phones.location_id')
                ->get();
        }


        return DataTables::of($dedicated)->make(true);
    }
}
