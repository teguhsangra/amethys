<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ParameterSettingController;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Booking;
use App\Models\FollowUp;
use App\Exports\BookingReminderExport;
use App\Models\Location;
use App\Models\RoomCategory;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Carbon\Carbon;

class BookingReminderController extends Controller
{
    private $url = 'booking_reminder';
    private $form_id = 'booking_reminder_form';
    private $table_name = 'booking_reminder';
    private $prefix_name = 'BR';
    private $ids = array();
    private $reminder_default_month;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->reminder_default_month = ParameterSettingController::getParameter("reminder_default_month");
    }

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
        $location_id = \Request::get('location_id');

        $data['location_id'] = $location_id;
        $data['room_category_id'] =  \Request::get('room_category_id');
        $data['renewal_status'] =  \Request::get('renewal_status');

        $data['location'] = Location::all();
        $data['room_categories'] = RoomCategory::where('code', '!=', 'LO')
            ->get();
        return view('pages.transaction.booking_reminder.index', $data);
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
        $validator = Validator::make($request->all(), [
            'remarks' => 'required',
            'follow_up_date' => 'required',
        ]);
        if ($validator->fails()) {
            \Session::flash('error', "You have to fill every form coloumn to input follow up");

            return Redirect::to($this->url);
        } else {
            $bookings_id = $request['booking_id'];
            $invoice_id = $request['invoice_id'];
            if ($bookings_id != null) {
                $follow_up_number = 1;
                $booking_follow_ups = FollowUp::Where([
                    'booking_id' => $bookings_id
                ])->get();

                $follow_up_number = $follow_up_number + sizeof($booking_follow_ups);
            } else {
                $follow_up_number = 1;
                $invoice_follow_ups = FollowUp::Where([
                    'invoice_id' => $invoice_id
                ])->get();

                $follow_up_number = $follow_up_number + sizeof($invoice_follow_ups);
            }

            DB::beginTransaction();
            $follow_up = new FollowUp;
            $follow_up->booking_id = $request['booking_id'];
            $follow_up->invoice_id = $request['invoice_id'];
            $follow_up->follow_up_number = $follow_up_number;
            $follow_up->remarks = $request['remarks'];
            $follow_up->follow_up_date = date('Y-m-d', strtotime($request['follow_up_date']));
            $follow_up->created_by = \Auth::user()->name;
            if ($follow_up->save()) {
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            } else {
                DB::rollBack();
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
        $booking = Booking::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'remarks' => 'required'
        ]);
        if ($validator->fails()) {
            \Session::flash('error', "You have to fill every form coloumn to input follow up");

            return Redirect::to($this->url);
        } else {
            DB::beginTransaction();
            $booking->discard_or_cancel_reason = $request['remarks'];
            $booking->renewal_status = $request['renewal_status'];
            if ($booking->save()) {
                DB::commit();
                $message = '';
                if ($request['renewal_status'] == "RN") {
                    $message = "Booking " . $booking->code . " is ready to renew";
                } else {
                    $message = "Booking " . $booking->code . " is ready to terminate";
                }
                \Session::flash('success', $message);
            } else {
                DB::rollBack();
                \Session::flash('error', 'You are failed in execute your data !!!');
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
        $booking = Booking::findOrFail($id);
        $booking->status_id = 4;
        if ($booking->save()) {
            \Session::flash('success', "Booking " . $booking->code . " is terminated");
        } else {
            \Session::flash('error', 'You are failed in execute your data !!!');
        }
        return Redirect::to($this->url);
    }


    public function get_child_of_this_employee($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);

        $show_data_by_structure = false;

        if ($a_g_and_module != null) {
            if ($a_g_and_module->showDataByStructure == 1) {
                $show_data_by_structure = true;
            }
        }

        if ($show_data_by_structure) {
            $employee = Employee::findOrFail($id);
            if (sizeof($employee->this_child) > 0) {
                foreach ($employee->this_child as $no => $detail) {
                    $this->ids[sizeof($this->ids)] = $detail->id;
                    $this->get_child_of_this_employee($detail->id);
                }
            }
        } else {
            $employees = Employee::where('id', '!=', $id)->get();
            foreach ($employees as $detail) {
                array_push($this->ids, $detail->id);
            }
        }
    }

    public function datatables(Request $request)
    {
        $renewal_status = $request['renewal_status'];
        // $reminder_end_date = date('Y-m-d', strtotime("+" . $this->reminder_default_month->int_value . " months"));
        $reminder_end_date = date('Y-m-d');

        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(1, 2);

        $location_id = \Request::get('location_id');
        $room_category_id =  \Request::get('room_category_id');
        $renewal_status =  \Request::get('renewal_status');

        if(!empty($location_id)){
            if(!empty($renewal_status)){
                if(!empty($room_category_id)){
                    if($room_category_id == "VO"){
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'product')
                            ->where('bookings.location_id', $location_id)
                            ->where('bookings.renewal_status', $renewal_status)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }else{
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'room')
                            ->where('bookings.room_category_id', $room_category_id)
                            ->where('bookings.location_id', $location_id)
                            ->where('bookings.renewal_status', $renewal_status)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }
                }else{
                    $bookings = Booking::select(
                        'bookings.*',
                        'locations.name as location_name',
                        'customers.name as customer_name',
                        'customers.email as customer_email',
                        DB::raw(
                            '(
                        CASE WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                        ),
                        'room_categories.code as room_category'
                    )
                        ->join('locations', 'bookings.location_id', 'locations.id')
                        ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                        ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                        ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('bookings.location_id', $location_id)
                        ->where('bookings.renewal_status', $renewal_status)
                        ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                        ->whereIn('employees.id', $this->ids)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->where('bookings.is_main_agreement', 'Y')
                        ->get();
                }
            }else{
                if(!empty($room_category_id)){
                    if($room_category_id == "VO"){
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.location_id', $location_id)
                            ->where('bookings.type', 'product')
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }else{
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.location_id', $location_id)
                            ->where('bookings.type', 'room')
                            ->where('bookings.room_category_id', $room_category_id)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }
                }else{
                    $bookings = Booking::select(
                        'bookings.*',
                        'locations.name as location_name',
                        'customers.name as customer_name',
                        'customers.email as customer_email',
                        DB::raw(
                            '(
                        CASE WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                        ),
                        'room_categories.code as room_category'
                    )
                        ->join('locations', 'bookings.location_id', 'locations.id')
                        ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                        ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                        ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('bookings.location_id', $location_id)
                        ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                        ->whereIn('employees.id', $this->ids)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->where('bookings.is_main_agreement', 'Y')
                        ->get();
                }
            }
        }else{
            if(!empty($renewal_status)){
                if(!empty($room_category_id)){
                    if($room_category_id == "VO"){
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'product')
                            ->where('bookings.renewal_status', $renewal_status)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }else{
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'room')
                            ->where('bookings.room_category_id', $room_category_id)
                            ->where('bookings.renewal_status', $renewal_status)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }
                }else{
                    $bookings = Booking::select(
                        'bookings.*',
                        'locations.name as location_name',
                        'customers.name as customer_name',
                        'customers.email as customer_email',
                        DB::raw(
                            '(
                        CASE WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                        ),
                        'room_categories.code as room_category'
                    )
                        ->join('locations', 'bookings.location_id', 'locations.id')
                        ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                        ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                        ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('bookings.renewal_status', $renewal_status)
                        ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                        ->whereIn('employees.id', $this->ids)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->where('bookings.is_main_agreement', 'Y')
                        ->get();
                }
            }else{
                if(!empty($room_category_id)){
                    if($room_category_id == "VO"){
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'product')
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }else{
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'room')
                            ->where('bookings.room_category_id', $room_category_id)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }
                }else{
                    $bookings = Booking::select(
                        'bookings.*',
                        'locations.name as location_name',
                        'customers.name as customer_name',
                        'customers.email as customer_email',
                        DB::raw(
                            '(
                        CASE WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                        ),
                        'room_categories.code as room_category'
                    )
                        ->join('locations', 'bookings.location_id', 'locations.id')
                        ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                        ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                        ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                        ->whereIn('employees.id', $this->ids)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->where('bookings.is_main_agreement', 'Y')
                        ->get();
                }
            }
        }
    
        return DataTables::of($bookings)
            ->editColumn('start_date', function ($data) {
                return date("j M Y", strtotime($data->start_date));
            })
            ->editColumn('end_date', function ($data) {
                return date("j M Y", strtotime($data->end_date));
            })
            ->make(true);
    }

    public function getDataBookingReminder(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $invoice_id = $request->input('invoice_id');

        if ($booking_id != null) {
            $booking_follow_ups = FollowUp::where('booking_id', $booking_id)->get();
        } else {
            $booking_follow_ups = FollowUp::where('invoice_id', $invoice_id)->get();
        }

        return \Response::json($booking_follow_ups);
    }

    public function exportToExcel(Request $request)
    {
        $renewal_status = $request['renewal_status'];
        // $reminder_end_date = date('Y-m-d', strtotime("+" . $this->reminder_default_month->int_value . " months"));
        $reminder_end_date = date('Y-m-d');

        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(1, 2);

        $location_id = \Request::get('location_id');
        $room_category_id =  \Request::get('room_category_id');
        $renewal_status =  \Request::get('renewal_status');

        if(!empty($location_id)){
            if(!empty($renewal_status)){
                if(!empty($room_category_id)){
                    if($room_category_id == "VO"){
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'product')
                            ->where('bookings.location_id', $location_id)
                            ->where('bookings.renewal_status', $renewal_status)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }else{
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'room')
                            ->where('bookings.room_category_id', $room_category_id)
                            ->where('bookings.location_id', $location_id)
                            ->where('bookings.renewal_status', $renewal_status)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }
                }else{
                    $bookings = Booking::select(
                        'bookings.*',
                        'locations.name as location_name',
                        'customers.name as customer_name',
                        'customers.email as customer_email',
                        DB::raw(
                            '(
                        CASE WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                        ),
                        'room_categories.code as room_category'
                    )
                        ->join('locations', 'bookings.location_id', 'locations.id')
                        ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                        ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                        ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('bookings.location_id', $location_id)
                        ->where('bookings.renewal_status', $renewal_status)
                        ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                        ->whereIn('employees.id', $this->ids)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->where('bookings.is_main_agreement', 'Y')
                        ->get();
                }
            }else{
                if(!empty($room_category_id)){
                    if($room_category_id == "VO"){
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.location_id', $location_id)
                            ->where('bookings.type', 'product')
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }else{
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.location_id', $location_id)
                            ->where('bookings.type', 'room')
                            ->where('bookings.room_category_id', $room_category_id)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }
                }else{
                    $bookings = Booking::select(
                        'bookings.*',
                        'locations.name as location_name',
                        'customers.name as customer_name',
                        'customers.email as customer_email',
                        DB::raw(
                            '(
                        CASE WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                        ),
                        'room_categories.code as room_category'
                    )
                        ->join('locations', 'bookings.location_id', 'locations.id')
                        ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                        ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                        ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('bookings.location_id', $location_id)
                        ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                        ->whereIn('employees.id', $this->ids)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->where('bookings.is_main_agreement', 'Y')
                        ->get();
                }
            }
        }else{
            if(!empty($renewal_status)){
                if(!empty($room_category_id)){
                    if($room_category_id == "VO"){
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'product')
                            ->where('bookings.renewal_status', $renewal_status)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }else{
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'room')
                            ->where('bookings.room_category_id', $room_category_id)
                            ->where('bookings.renewal_status', $renewal_status)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }
                }else{
                    $bookings = Booking::select(
                        'bookings.*',
                        'locations.name as location_name',
                        'customers.name as customer_name',
                        'customers.email as customer_email',
                        DB::raw(
                            '(
                        CASE WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                        ),
                        'room_categories.code as room_category'
                    )
                        ->join('locations', 'bookings.location_id', 'locations.id')
                        ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                        ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                        ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('bookings.renewal_status', $renewal_status)
                        ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                        ->whereIn('employees.id', $this->ids)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->where('bookings.is_main_agreement', 'Y')
                        ->get();
                }
            }else{
                if(!empty($room_category_id)){
                    if($room_category_id == "VO"){
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'product')
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }else{
                        $bookings = Booking::select(
                            'bookings.*',
                            'locations.name as location_name',
                            'customers.name as customer_name',
                            'customers.email as customer_email',
                            DB::raw(
                                '(
                            CASE WHEN bookings.type = "package" THEN "Package"
                            WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                            ),
                            'room_categories.code as room_category'
                        )
                            ->join('locations', 'bookings.location_id', 'locations.id')
                            ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                            ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                            ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                            ->where('bookings.type', 'room')
                            ->where('bookings.room_category_id', $room_category_id)
                            ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                            ->whereIn('employees.id', $this->ids)
                            ->whereIn('bookings.status_id', $active_status_id)
                            ->where('bookings.is_main_agreement', 'Y')
                            ->get();
                    }
                }else{
                    $bookings = Booking::select(
                        'bookings.*',
                        'locations.name as location_name',
                        'customers.name as customer_name',
                        'customers.email as customer_email',
                        DB::raw(
                            '(
                        CASE WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "product" THEN "Product And Service"  ELSE "Rooms" END) AS bookings_type'
                        ),
                        'room_categories.code as room_category'
                    )
                        ->join('locations', 'bookings.location_id', 'locations.id')
                        ->leftJoin('customers', 'bookings.customer_id', 'customers.id')
                        ->leftJoin('employees', 'bookings.employee_id', 'employees.id')
                        ->leftJoin('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where(DB::raw('DATE_ADD(bookings.end_date, INTERVAL -bookings.term_notice_period MONTH)'), '<=', $reminder_end_date)
                        ->whereIn('employees.id', $this->ids)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->where('bookings.is_main_agreement', 'Y')
                        ->get();
                }
            }
        }

        $data['bookings'] = $bookings;

        return Excel::download(new BookingReminderExport($data), 'Reminder_recap_' . $renewal_status . '_' . Carbon::now()->format("j F Y") . '.xlsx');
    }
}
