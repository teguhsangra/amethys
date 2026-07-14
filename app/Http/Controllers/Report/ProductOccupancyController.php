<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Exports\ProductOccupancyExport;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Employee;
use App\Models\Location;
use DataTables;
use Validator;
use DateTime;
use Redirect;
use Excel;
use Auth;
use DB;

class ProductOccupancyController extends Controller
{
    private $url = 'product_occupancy_report';
    private $form_id = 'product_occupancy_report_form';
    private $table_name = 'bookings';
    private $prefix_name = 'PRO';
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

    public function index(Request $request)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;

        $data['locations'] = \Auth::user()->location;
        $locations_id = $request->input('locations_id');
        $start_month = $request->input('start_month');
        $start_year = $request->input('start_year');
        $end_month = $request->input('end_month');
        $end_year = $request->input('end_year');
        if ($locations_id == null) {
            $locations_id = $data['locations'][0]->id;
        }
        $data['location_id'] = $locations_id;

        $data['selected_location'] = DB::table('locations')->where('id', $locations_id)->first();
        if ($start_month == null || $start_month == "undefined") {
            $data['start_month'] = "01";
        } else {
            $data['start_month'] = $start_month;
        }

        if ($start_year == null || $start_year == "undefined") {
            $data['start_year'] = date("Y");
        } else {
            $data['start_year'] = $start_year;
        }

        if ($end_month == null || $end_month == "undefined") {
            $data['end_month'] = "12";
        } else {
            $data['end_month'] = $end_month;
        }

        if ($end_year == null || $end_year == "undefined") {
            $data['end_year'] = date("Y");
        } else {
            $data['end_year'] = $end_year;
        }

        $string_of_first_date = $data['start_year'] . "-" . $data['start_month'] . "-01";
        $first_of_start_date = new DateTime(date("Y-m-d", strtotime($string_of_first_date)));

        $first_of_end_date = $data['end_year'] . "-" . $data['end_month'] . "-01";
        $end_of_end_date = new DateTime(date("Y-m-t", strtotime($first_of_end_date)));

        $so_booking_details = DB::table('booking_details')->where('room_id', '!=', null)->where('start_date', '<=', $end_of_end_date)->get();

        $date_interval = $first_of_start_date->diff($end_of_end_date);
        if ($date_interval->y > 0) {
            $total_month = $date_interval->m + ($date_interval->y * 12);
        } else {
            $total_month = $date_interval->m;
        }

        $array_of_booking_price = array();
        $array_of_str_month = array();
        $array_of_str_year = array();
        $array_total_revenue_of_month = array();
        $array_total_vo_of_month = array();
        $array_total_occupied_sqm_of_month = array();

        $active_status_id = array(1, 2);
        for ($i = 0; $i <= $total_month; $i++){
            $this_start_date = date("Y-m-d", strtotime("+".$i." month", strtotime($first_of_start_date->format('Y-m-d'))));
            $this_end_date = date("Y-m-t", strtotime($this_start_date));
            $array_of_str_month[$i] = date("M", strtotime($this_start_date));
            $array_of_str_year[$i] = date("Y", strtotime($this_start_date));

            $array_total_revenue_of_month[$i] = DB::table('booking_details')
                ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                ->where('bookings.location_id', $locations_id)
                ->where('bookings.type', 'product')
                ->where('booking_details.start_date', '>=', $this_start_date)
                ->where('booking_details.start_date', '<=', $this_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->sum('booking_details.detail_price');
            
            $array_total_vo_of_month[$i] = DB::table('bookings')
                ->where('type', 'product')
                ->where('end_date', '>=', $this_start_date)
                ->where('start_date', '<=', $this_end_date)
                ->where('location_id', $locations_id)
                ->whereIn('status_id', $active_status_id)
                ->count();
        }

        $bookings = Booking::where('type', 'product')
            ->where('start_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->where('end_date', '>=', $first_of_start_date->format('Y-m-d'))
            ->where('location_id', $locations_id)
            ->whereIn('bookings.status_id', $active_status_id)
            ->get();

        foreach($bookings as $no => $booking){
            for ($i = 0; $i <= $total_month; $i++){
                $this_start_date = date("Y-m-d", strtotime("+".$i." month", strtotime($first_of_start_date->format('Y-m-d'))));
                $month = intval(date("m", strtotime($this_start_date)));
                $year = date("Y", strtotime($this_start_date));

                $array_of_booking_price[$no][$i] = DB::table('booking_details')
                    ->where('booking_id', $booking->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->sum('detail_price');
            }
        }


        $data['total_month'] = $total_month;
        $data['first_of_start_date'] = $first_of_start_date->format('Y-m-d');
        $data['end_of_end_date'] = $end_of_end_date->format('Y-m-d');
        $data['array_of_str_month'] = $array_of_str_month;
        $data['array_of_str_year'] = $array_of_str_year;
        $data['array_total_revenue_of_month'] = $array_total_revenue_of_month;
        $data['array_total_vo_of_month'] = $array_total_vo_of_month;
        $data['array_of_booking_price'] = $array_of_booking_price;
        $data['bookings'] = $bookings;

        return view('pages.report.product.index', $data);
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
        $data['locations'] = \Auth::user()->location;
        $locations_id = $request->input('locations_id');
        $start_month = $request->input('start_month');
        $start_year = $request->input('start_year');
        $end_month = $request->input('end_month');
        $end_year = $request->input('end_year');
        if ($locations_id == null) {
            $locations_id = $data['locations'][0]->id;
        }
        $data['location_id'] = $locations_id;
        $data['location'] = Location::findOrFail($locations_id);

        $data['selected_location'] = DB::table('locations')->where('id', $locations_id)->first();
        if ($start_month == null || $start_month == "undefined") {
            $data['start_month'] = "01";
        } else {
            $data['start_month'] = $start_month;
        }

        if ($start_year == null || $start_year == "undefined") {
            $data['start_year'] = date("Y");
        } else {
            $data['start_year'] = $start_year;
        }

        if ($end_month == null || $end_month == "undefined") {
            $data['end_month'] = "12";
        } else {
            $data['end_month'] = $end_month;
        }

        if ($end_year == null || $end_year == "undefined") {
            $data['end_year'] = date("Y");
        } else {
            $data['end_year'] = $end_year;
        }

        $string_of_first_date = $data['start_year'] . "-" . $data['start_month'] . "-01";
        $first_of_start_date = new DateTime(date("Y-m-d", strtotime($string_of_first_date)));

        $first_of_end_date = $data['end_year'] . "-" . $data['end_month'] . "-01";
        $end_of_end_date = new DateTime(date("Y-m-t", strtotime($first_of_end_date)));

        $so_booking_details = DB::table('booking_details')->where('room_id', '!=', null)->where('start_date', '<=', $end_of_end_date)->get();

        $date_interval = $first_of_start_date->diff($end_of_end_date);
        if ($date_interval->y > 0) {
            $total_month = $date_interval->m + ($date_interval->y * 12);
        } else {
            $total_month = $date_interval->m;
        }

        $array_of_booking_price = array();
        $array_of_str_month = array();
        $array_of_str_year = array();
        $array_total_revenue_of_month = array();
        $array_total_vo_of_month = array();
        $array_total_occupied_sqm_of_month = array();

        $active_status_id = array(1, 2);
        for ($i = 0; $i <= $total_month; $i++){
            $this_start_date = date("Y-m-d", strtotime("+".$i." month", strtotime($first_of_start_date->format('Y-m-d'))));
            $this_end_date = date("Y-m-t", strtotime($this_start_date));
            $array_of_str_month[$i] = date("M", strtotime($this_start_date));
            $array_of_str_year[$i] = date("Y", strtotime($this_start_date));

            $array_total_revenue_of_month[$i] = DB::table('booking_details')
                ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                ->where('bookings.location_id', $locations_id)
                ->where('bookings.type', 'product')
                ->where('booking_details.start_date', '>=', $this_start_date)
                ->where('booking_details.start_date', '<=', $this_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->sum('booking_details.detail_price');
            
            $array_total_vo_of_month[$i] = DB::table('bookings')
                ->where('type', 'product')
                ->where('end_date', '>=', $this_start_date)
                ->where('start_date', '<=', $this_end_date)
                ->where('location_id', $locations_id)
                ->whereIn('status_id', $active_status_id)
                ->count();
        }

        $bookings = Booking::where('type', 'product')
            ->where('start_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->where('end_date', '>=', $first_of_start_date->format('Y-m-d'))
            ->where('location_id', $locations_id)
            ->whereIn('bookings.status_id', $active_status_id)
            ->get();

        foreach($bookings as $no => $booking){
            for ($i = 0; $i <= $total_month; $i++){
                $this_start_date = date("Y-m-d", strtotime("+".$i." month", strtotime($first_of_start_date->format('Y-m-d'))));
                $month = intval(date("m", strtotime($this_start_date)));
                $year = date("Y", strtotime($this_start_date));

                $array_of_booking_price[$no][$i] = DB::table('booking_details')
                    ->where('booking_id', $booking->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->sum('detail_price');
            }
        }

        $data['total_term'] = Booking::where('type', 'product')
            ->where('start_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->where('end_date', '>=', $first_of_start_date->format('Y-m-d'))
            ->where('location_id', $locations_id)
            ->whereIn('bookings.status_id', $active_status_id)
            ->sum('length_of_term');

        $data['total_month'] = $total_month;
        $data['first_of_start_date'] = $first_of_start_date->format('Y-m-d');
        $data['end_of_end_date'] = $end_of_end_date->format('Y-m-d');
        $data['array_of_str_month'] = $array_of_str_month;
        $data['array_of_str_year'] = $array_of_str_year;
        $data['array_total_revenue_of_month'] = $array_total_revenue_of_month;
        $data['array_total_vo_of_month'] = $array_total_vo_of_month;
        $data['array_of_booking_price'] = $array_of_booking_price;
        $data['bookings'] = $bookings;

        return Excel::download(new ProductOccupancyExport($data), 'Reports_VO_Occupancy_' . date('YmdHis') . '.xlsx');
    }
}
