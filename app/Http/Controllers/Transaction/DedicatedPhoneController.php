<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Transaction\SalesActivityController;
use App\Models\Booking;
use App\Models\ParameterSetting;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\DedicatedPhone;
use App\Models\HistoryDedicatedPhone;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class DedicatedPhoneController extends Controller
{
    private $url = 'dedicated_phone_transaction';
    private $form_id = 'dedicated_phone_transaction_form';
    private $table_name = 'dedicated_phones';
    private $prefix_name = 'DP';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['employee'] = $employee;
        return view('pages.transaction.dedicated_phone.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['booking'] = Booking::all();
        $data['dedicated_phones'] = DedicatedPhone::leftJoin('booking_dedicated_phones', 'booking_dedicated_phones.dedicated_phone_id', 'dedicated_phones.id')
            ->where('booking_dedicated_phones.dedicated_phone_id', null)
            ->where('dedicated_phones.customer_id', null)
            ->select('dedicated_phones.*')
            ->get();
        $not_active_booking = DedicatedPhone::join('booking_dedicated_phones', 'booking_dedicated_phones.dedicated_phone_id', 'dedicated_phones.id')
            ->join('bookings', 'bookings.id', 'booking_dedicated_phones.booking_id')
            ->whereNotIn('bookings.status_id', array(1, 2, 4))
            ->where('dedicated_phones.customer_id', null)
            ->select('dedicated_phones.*')
            ->get();
        $data['dedicated_phones'] = $not_active_booking->merge($data['dedicated_phones']);

        return view('pages.transaction.dedicated_phone.editor', $data);
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
            'customer_id' => 'required',
            'booking_id' => 'required',
            'dedicated_phone_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            if ($request['activity'] == 'activation') {
                $histoy =  new HistoryDedicatedPhone;
                $histoy->user_id  = Auth::user()->id;
                $histoy->dedicated_phone_id = $request['dedicated_phone_id'];
                $histoy->customer_id = $request['customer_id'];
                $histoy->booking_id = $request['booking_id'];
                $histoy->activity = $request['activity'];
                $histoy->remarks = $request['remarks'];
                if ($histoy->save()) {
                    $dedicated = DedicatedPhone::where('id', $request['dedicated_phone_id'])->first();
                    $dedicated->customer_id = $request['customer_id'];
                    $dedicated->extension_no = $request['extension_no'];
                    $dedicated->forward_to = $request['forward_to'];
                    $dedicated->display_name = $request['display_name'];
                    $dedicated->status = 'active';
                    if (!$dedicated->save()) {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                    DB::commit();
                    \Session::flash('success', 'You are success in inputing your data');
                } else {
                    DB::rollBack();
                    \Session::flash('error', 'You are failed in updating your data !!!');
                }
            } else {
                for ($i = 0; $i < sizeof($request['dedicated_phone_id']); $i++) {
                    $histoy = new HistoryDedicatedPhone;
                    $histoy->user_id  = Auth::user()->id;
                    $histoy->dedicated_phone_id = $request['dedicated_phone_id'][$i];
                    $histoy->customer_id = $request['customer_id'];
                    $histoy->booking_id = $request['booking_id'];
                    $histoy->activity = 'activation';
                    $histoy->remarks = $request['remarks'];
                    if (!$histoy->save()) {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }

                $booking = Booking::findOrFail($request['booking_id']);
                DB::table('booking_dedicated_phones')->where('booking_id', $booking->id)->delete();
                for ($i = 0; $i < sizeof($request['dedicated_phone_id']); $i++) {
                    $booking->dedicated_phones()->attach($request['dedicated_phone_id'][$i]);
                    $dedicated = DedicatedPhone::where('id', $request['dedicated_phone_id'][$i])->first();
                    $dedicated->location_id = $request['location_id'];
                    $dedicated->customer_id = $request['customer_id'];
                    if (!$dedicated->save()) {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
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
        //
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

        DB::beginTransaction();

        if ($request['edit'] == "edit") {
            $histoy =  HistoryDedicatedPhone::where('dedicated_phone_id', $id)->first();
            $histoy->user_id  = Auth::user()->id;
        } else {
            $histoy =  HistoryDedicatedPhone::where('dedicated_phone_id', $id)->first();
            $histoy->user_id  = Auth::user()->id;
            $histoy->activity = $request['activity'];
            $histoy->remarks = $request['remarks'];
        }

        if ($histoy->save()) {
            if ($request['activity'] == 'deactivation') {

                $dedicated = DedicatedPhone::where('id', $histoy->dedicated_phone_id)->first();
                $dedicated->customer_id = null;
                $dedicated->extension_no = null;
                $dedicated->forward_to = null;
                $dedicated->display_name = null;
                if (!$dedicated->save()) {
                    DB::rollBack();
                    \Session::flash('error', 'You are failed in updating your data !!!');
                }
            } else {
                $dedicated = DedicatedPhone::where('id', $histoy->dedicated_phone_id)->first();
                $dedicated->extension_no = $request['extension_no'];
                $dedicated->forward_to = $request['forward_to'];
                $dedicated->display_name = $request['display_name'];
                $dedicated->remarks = $request['remarks'];
                if (!$dedicated->save()) {
                    DB::rollBack();
                    \Session::flash('error', 'You are failed in updating your data !!!');
                }
            }


            DB::commit();
            \Session::flash('success', 'You are success in updating your data');
        } else {
            DB::rollBack();
            \Session::flash('error', 'You are failed in updating your data !!!');
        }
        return Redirect::to($this->url);
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

    public function datatables(Request $request)
    {
        $activity = $request['activity'];

        if ($activity == "activation") {
            $dedicated_phones = Booking::join('booking_dedicated_phones', 'booking_dedicated_phones.booking_id', 'bookings.id')
                ->join('dedicated_phones', 'dedicated_phones.id', 'booking_dedicated_phones.dedicated_phone_id')
                ->join('customers', 'customers.id', 'bookings.customer_id')
                ->join('locations', 'locations.id', 'bookings.location_id')
                ->select('dedicated_phones.*', 'dedicated_phones.id as dedicated_phone_id', 'bookings.id as booking_id', 'dedicated_phones.id as dedicated_phone_id', 'locations.name as location_name', 'customers.id as customer_id', 'customers.name as customer_name', 'bookings.code as booking_code', 'bookings.id as booking_id')
                ->where('dedicated_phones.customer_id', '!=', null)
                ->where('dedicated_phones.status', 'active')
                ->get();
        } else {
            $dedicated_phones = Booking::join('booking_dedicated_phones', 'booking_dedicated_phones.booking_id', 'bookings.id')
                ->join('dedicated_phones', 'dedicated_phones.id', 'booking_dedicated_phones.dedicated_phone_id')
                ->join('customers', 'customers.id', 'bookings.customer_id')
                ->join('locations', 'locations.id', 'bookings.location_id')
                ->select('dedicated_phones.*', 'dedicated_phones.id as dedicated_phone_id', 'bookings.id as booking_id', 'dedicated_phones.id as dedicated_phone_id', 'locations.name as location_name', 'customers.id as customer_id', 'customers.name as customer_name', 'bookings.code as booking_code', 'bookings.id as booking_id')
                ->where('dedicated_phones.customer_id', '!=', null)
                ->where('dedicated_phones.status', null)
                ->get();
        }

        return DataTables::of($dedicated_phones)
            ->make(true);
    }

    public function get_by_id($id)
    {
        return Booking::select('bookings.start_date as start_date', 'bookings.end_date as end_date', 'customers.id as id', 'customers.name as name')
            ->join('customers', 'customers.id', 'bookings.customer_id')
            ->where('bookings.id', $id)
            ->first();
    }

    public function get_by_booking($id)
    {

        return Booking::select('booking_dedicated_phones.dedicated_phone_id as dedicated_phone_id')
            ->join('booking_dedicated_phones', 'booking_dedicated_phones.booking_id', 'bookings.id')
            ->where('bookings.id', $id)
            ->get();
    }

    public function getDedicated($id)
    {
        $return = DedicatedPhone::where('id', $id)->first();

        return $return;
    }
}
