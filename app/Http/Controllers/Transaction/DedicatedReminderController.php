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

class DedicatedReminderController extends Controller
{
    private $url = 'dedicated_phone_reminder';
    private $form_id = 'dedicated_phone_reminder_form';
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
        return view('pages.transaction.dedicated_reminder.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $reminder_end_date = date('Y-m-d', strtotime("+" . '7' . " days"));
        $dedicated_phones = Booking::join('history_dedicated_phones', 'history_dedicated_phones.booking_id', 'bookings.id')
            ->join('dedicated_phones', 'dedicated_phones.id', 'history_dedicated_phones.dedicated_phone_id')
            ->join('customers', 'customers.id', 'dedicated_phones.customer_id')
            ->join('locations', 'locations.id', 'dedicated_phones.location_id')
            ->select('locations.name as location_name', 'customers.name as customer_name', 'dedicated_phones.id as dedicated_phone_id', 'dedicated_phones.number as number', 'dedicated_phones.type as type', 'bookings.start_date', 'bookings.end_date')
            ->where('bookings.end_date', '<=', $reminder_end_date)
            ->get();

        return DataTables::of($dedicated_phones)
            ->make(true);
    }
}
