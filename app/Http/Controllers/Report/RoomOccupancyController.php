<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Location;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\Employee;
use App\Exports\RoomOccupancyExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Carbon\Carbon;
use DateTime;

class RoomOccupancyController extends Controller
{

    private $url = 'room_occupancy_report';
    private $form_id = 'room_occupancy_report_form';
    private $table_name = 'bookings';
    private $prefix_name = 'ROOM';
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
        $location_id = $request->input('locations_id');
        $type = $request->input('type');
        $start_month = $request->input('start_month');
        $start_year = $request->input('start_year');
        $end_month = $request->input('end_month');
        $end_year = $request->input('end_year');
        if ($location_id == null) {
            $location_id = $data['locations'][0]->id;
        }
        if($type == null || $type == "undefined"){
            $data['type'] = 'SO';
        }else{
            $data['type'] = $type;
        }

        $data['location_id'] = $location_id;

        $data['selected_location'] = DB::table('locations')->where('id', $location_id)->first();
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

        $date_interval = $first_of_start_date->diff($end_of_end_date);
        if ($date_interval->y > 0) {
            $total_month = $date_interval->m + ($date_interval->y * 12);
        } else {
            $total_month = $date_interval->m;
        }


        $total_sqm_room = Room::select(DB::raw('SUM(sqm) as total_sqm'))
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'room_categories.id', 'r_c_and_room.room_category_id')
            ->where('room_categories.code', $data['type'])
            ->where('rooms.location_id', $location_id)
            ->first();


        $data['total_sqm'] = $total_sqm_room->total_sqm;


        $total_used_sqm = Booking::select(DB::raw('SUM(rooms.sqm) as total_sqm'))
            ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
            ->join('rooms', 'booking_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
            ->where('room_categories.code', $data['type'])
            ->where('bookings.location_id', $location_id)
            ->where('bookings.type', 'room')
            ->where('bookings.end_date', '>=', date('Y-m-d'))
            ->first();

        $data['used_sqm'] = $total_used_sqm->total_sqm;

        $data['available_sqm'] = $data['total_sqm'] - $data['used_sqm'];

        $array_of_first_month = array();
        $array_of_str_month = array();
        $array_of_str_year = array();
        $array_of_end_month = array();
        $array_total_revenue_of_month = array();
        $array_total_occupied_office_of_month = array();
        $array_total_occupied_sqm_of_month = array();
        $array_of_booking = array();
        $array_of_booking_price = array();

        $active_status_id = array(1, 2);

        for ($i = 0; $i <= $total_month; $i++) {
            $this_start_date = date("Y-m-d", strtotime("+".$i." month", strtotime($first_of_start_date->format('Y-m-d'))));
            $this_end_date = date("Y-m-t", strtotime($this_start_date));
            $array_of_str_month[$i] = date("M", strtotime($this_start_date));
            $array_of_str_year[$i] = date("Y", strtotime($this_start_date));

            $array_total_revenue_of_month[$i] = DB::table('booking_details')
                ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', $data['type'])
                ->where('bookings.location_id', $location_id)
                ->where('booking_details.start_date', '>=', $this_start_date)
                ->where('booking_details.start_date', '<=', $this_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->sum('booking_details.detail_price');

            $array_total_occupied_office_of_month[$i] = DB::table('bookings')
                ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                ->where('room_categories.code', $data['type'])
                ->where('bookings.location_id', $location_id)
                ->where('bookings.end_date', '>=', $this_start_date)
                ->where('bookings.start_date', '<=', $this_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->count();

            $array_total_occupied_sqm_of_month[$i] = DB::table('booking_details')
                ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                ->join('rooms', 'booking_details.room_id', 'rooms.id')
                ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', $data['type'])
                ->where('bookings.location_id', $location_id)
                ->where('booking_details.start_date', '>=', $this_start_date)
                ->where('booking_details.start_date', '<=', $this_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->sum('rooms.sqm');
        }

        $rooms = Room::select('rooms.*')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'room_categories.id', 'r_c_and_room.room_category_id')
            ->where('room_categories.code', $data['type'])
            ->where('rooms.location_id', $location_id)
            ->get();
        
        foreach($rooms as $no => $room){
            $array_of_booking[$no] = Booking::select('bookings.*', 'booking_and_room.detail_price as quoted_price')
                ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                ->where('booking_and_room.room_id', $room->id)
                ->where('bookings.end_date', '>=', $first_of_start_date->format('Y-m-d'))
                ->where('bookings.start_date', '<=', $end_of_end_date->format('Y-m-d'))
                ->whereIn('bookings.status_id', $active_status_id)
                ->get();

            for ($i = 0; $i <= $total_month; $i++){
                $this_start_date = date("Y-m-d", strtotime("+".$i." month", strtotime($first_of_start_date->format('Y-m-d'))));
                $month = intval(date("m", strtotime($this_start_date)));
                $year = date("Y", strtotime($this_start_date));

                $array_of_booking_price[$no][$i] = DB::table('booking_details')
                    ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                    ->where('booking_details.room_id', $room->id)
                    ->where('booking_details.month', $month)
                    ->where('booking_details.year', $year)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->sum('booking_details.detail_price');
            }
        }

        $data['total_month'] = $total_month;
        $data['first_of_start_date'] = $first_of_start_date->format('Y-m-d');
        $data['end_of_end_date'] = $end_of_end_date->format('Y-m-d');
        $data['array_of_str_month'] = $array_of_str_month;
        $data['array_of_str_year'] = $array_of_str_year;
        $data['array_total_revenue_of_month'] = $array_total_revenue_of_month;
        $data['array_total_occupied_office_of_month'] = $array_total_occupied_office_of_month;
        $data['array_total_occupied_sqm_of_month'] = $array_total_occupied_sqm_of_month;
        $data['array_of_booking_price'] = $array_of_booking_price;
        $data['array_of_booking'] = $array_of_booking;
        
        $data['total_term'] = Booking::where('end_date', '>=', $first_of_start_date->format('Y-m-d'))
            ->where('start_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->whereIn('status_id', $active_status_id)
            ->sum('length_of_term');
        
        $data['total_booking'] = Booking::where('end_date', '>=', $first_of_start_date->format('Y-m-d'))
            ->where('start_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->whereIn('status_id', $active_status_id)
            ->count();
        
        $data['rooms'] = $rooms;
        $data['total_sqm'] = Room::select('rooms.*')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'room_categories.id', 'r_c_and_room.room_category_id')
            ->where('room_categories.code', $data['type'])
            ->where('rooms.location_id', $location_id)
            ->sum('sqm');

        return view('pages.report.room.index', $data);
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
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['locations'] = \Auth::user()->location;
        $location_id = $request->input('locations_id');
        $type = $request->input('type');
        $start_month = $request->input('start_month');
        $start_year = $request->input('start_year');
        $end_month = $request->input('end_month');
        $end_year = $request->input('end_year');
        if ($location_id == null) {
            $location_id = $data['locations'][0]->id;
        }
        if($type == null || $type == "undefined"){
            $data['type'] = 'SO';
        }else{
            $data['type'] = $type;
        }

        $data['location_id'] = $location_id;
        $data['location'] = Location::findOrFail($location_id);

        $data['selected_location'] = DB::table('locations')->where('id', $location_id)->first();
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

        $date_interval = $first_of_start_date->diff($end_of_end_date);
        if ($date_interval->y > 0) {
            $total_month = $date_interval->m + ($date_interval->y * 12);
        } else {
            $total_month = $date_interval->m;
        }


        $total_sqm_room = Room::select(DB::raw('SUM(sqm) as total_sqm'))
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'room_categories.id', 'r_c_and_room.room_category_id')
            ->where('room_categories.code', $data['type'])
            ->where('rooms.location_id', $location_id)
            ->first();


        $data['total_sqm'] = $total_sqm_room->total_sqm;


        $total_used_sqm = Booking::select(DB::raw('SUM(rooms.sqm) as total_sqm'))
            ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
            ->join('rooms', 'booking_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
            ->where('room_categories.code', $data['type'])
            ->where('bookings.location_id', $location_id)
            ->where('bookings.type', 'room')
            ->where('bookings.end_date', '>=', date('Y-m-d'))
            ->first();

        $data['used_sqm'] = $total_used_sqm->total_sqm;

        $data['available_sqm'] = $data['total_sqm'] - $data['used_sqm'];

        $array_of_first_month = array();
        $array_of_str_month = array();
        $array_of_str_year = array();
        $array_of_end_month = array();
        $array_total_revenue_of_month = array();
        $array_total_occupied_office_of_month = array();
        $array_total_occupied_sqm_of_month = array();
        $array_of_booking = array();
        $array_of_booking_price = array();

        $active_status_id = array(1, 2);

        for ($i = 0; $i <= $total_month; $i++) {
            $this_start_date = date("Y-m-d", strtotime("+".$i." month", strtotime($first_of_start_date->format('Y-m-d'))));
            $this_end_date = date("Y-m-t", strtotime($this_start_date));
            $array_of_str_month[$i] = date("M", strtotime($this_start_date));
            $array_of_str_year[$i] = date("Y", strtotime($this_start_date));

            $array_total_revenue_of_month[$i] = DB::table('booking_details')
                ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', $data['type'])
                ->where('bookings.location_id', $location_id)
                ->where('booking_details.start_date', '>=', $this_start_date)
                ->where('booking_details.start_date', '<=', $this_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->sum('booking_details.detail_price');

            $array_total_occupied_office_of_month[$i] = DB::table('bookings')
                ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                ->where('room_categories.code', $data['type'])
                ->where('bookings.location_id', $location_id)
                ->where('bookings.end_date', '>=', $this_start_date)
                ->where('bookings.start_date', '<=', $this_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->count();

            $array_total_occupied_sqm_of_month[$i] = DB::table('booking_details')
                ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                ->join('rooms', 'booking_details.room_id', 'rooms.id')
                ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', $data['type'])
                ->where('bookings.location_id', $location_id)
                ->where('booking_details.start_date', '>=', $this_start_date)
                ->where('booking_details.start_date', '<=', $this_end_date)
                ->whereIn('bookings.status_id', $active_status_id)
                ->sum('rooms.sqm');
        }

        $rooms = Room::select('rooms.*')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'room_categories.id', 'r_c_and_room.room_category_id')
            ->where('room_categories.code', $data['type'])
            ->where('rooms.location_id', $location_id)
            ->get();
        
        foreach($rooms as $no => $room){
            $array_of_booking[$no] = Booking::select('bookings.*', 'booking_and_room.detail_price as quoted_price')
                ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                ->where('booking_and_room.room_id', $room->id)
                ->where('bookings.end_date', '>=', $first_of_start_date->format('Y-m-d'))
                ->where('bookings.start_date', '<=', $end_of_end_date->format('Y-m-d'))
                ->whereIn('bookings.status_id', $active_status_id)
                ->get();

            for ($i = 0; $i <= $total_month; $i++){
                $this_start_date = date("Y-m-d", strtotime("+".$i." month", strtotime($first_of_start_date->format('Y-m-d'))));
                $month = intval(date("m", strtotime($this_start_date)));
                $year = date("Y", strtotime($this_start_date));

                $array_of_booking_price[$no][$i] = DB::table('booking_details')
                    ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                    ->where('booking_details.room_id', $room->id)
                    ->where('booking_details.month', $month)
                    ->where('booking_details.year', $year)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->sum('booking_details.detail_price');
            }
        }

        $data['total_month'] = $total_month;
        $data['first_of_start_date'] = $first_of_start_date->format('Y-m-d');
        $data['end_of_end_date'] = $end_of_end_date->format('Y-m-d');
        $data['array_of_str_month'] = $array_of_str_month;
        $data['array_of_str_year'] = $array_of_str_year;
        $data['array_total_revenue_of_month'] = $array_total_revenue_of_month;
        $data['array_total_occupied_office_of_month'] = $array_total_occupied_office_of_month;
        $data['array_total_occupied_sqm_of_month'] = $array_total_occupied_sqm_of_month;
        $data['array_of_booking_price'] = $array_of_booking_price;
        $data['array_of_booking'] = $array_of_booking;
        
        $data['total_term'] = Booking::where('end_date', '>=', $first_of_start_date->format('Y-m-d'))
            ->where('start_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->whereIn('status_id', $active_status_id)
            ->sum('length_of_term');
        
        $data['total_booking'] = Booking::where('end_date', '>=', $first_of_start_date->format('Y-m-d'))
            ->where('start_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->whereIn('status_id', $active_status_id)
            ->count();
        
        $data['rooms'] = $rooms;
        $data['total_sqm'] = Room::select('rooms.*')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'room_categories.id', 'r_c_and_room.room_category_id')
            ->where('room_categories.code', $data['type'])
            ->where('rooms.location_id', $location_id)
            ->sum('sqm');

        return Excel::download(new RoomOccupancyExport($data), 'Reports_Room_Occupancy_' . Carbon::now()->format("j F Y") . '.xlsx');
    }
}
