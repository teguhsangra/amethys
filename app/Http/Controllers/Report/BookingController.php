<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\RoomCategory;
use App\Exports\BookingExports;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Carbon\Carbon;

class BookingController extends Controller
{

    private $url = 'booking_report';
    private $form_id = 'booking_report_form';
    private $table_name = 'bookings';
    private $prefix_name = 'BO';
    private $ids = array();
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['start_date'] = Carbon::now();
        $data['end_date'] = Carbon::now();
        $data['room_categories'] = RoomCategory::where('code', '!=', 'LO')
            ->get();
        return view('pages.report.booking.index', $data);
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
        //
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
        //
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
    public function exportToExcel(Request $request)
    {
        return Excel::download(new BookingExports($request['room_category_id'], $request['start_date'], $request['end_date']), 'Reports_Booking_' . $request['days'] . '_' . Carbon::now()->format("j F Y") . '.xlsx');
    }

    public function get_child_of_this_employee($id)
    {
        $employee = Employee::findOrFail($id);
        if (sizeof($employee->this_child) > 0) {
            foreach ($employee->this_child as $no => $detail) {
                $this->ids[sizeof($this->ids)] = $detail->id;
                $this->get_child_of_this_employee($detail->id);
            }
        }
    }

    public function datatables(Request $request)
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $room_category_id = $request->input('room_category_id');

        if ($start_date != null && $end_date != null) {
            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'bookings.created_at',
                'bookings.posting_by'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->join('customers', 'bookings.customer_id',  'customers.id')
                ->join('contacts', 'bookings.contact_id',  'contacts.id')
                ->where('bookings.is_main_agreement', 'Y')
                ->where('bookings.created_at', '>=', $start_date)
                ->where('bookings.created_at', '<=', $end_date)
                ->get();
        } else if ($room_category_id != null && $start_date != null && $end_date != null) {
            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'bookings.created_at',
                'bookings.posting_by'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->join('customers', 'bookings.customer_id',  'customers.id')
                ->join('contacts', 'bookings.contact_id',  'contacts.id')
                ->where('bookings.is_main_agreement', 'Y')
                ->where('bookings.room_category_id', $room_category_id)
                ->where('bookings.created_at', '>=', $start_date)
                ->where('bookings.created_at', '<=', $end_date)
                ->get();
        } else {
            $bookings = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'bookings.created_at',
                'bookings.posting_by'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->join('customers', 'bookings.customer_id',  'customers.id')
                ->join('contacts', 'bookings.contact_id',  'contacts.id')
                ->get();
        }
        return Datatables::of($bookings)->make(true);
    }
}
