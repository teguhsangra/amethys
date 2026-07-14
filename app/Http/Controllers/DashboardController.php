<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParameterSetting;
use App\Models\Room;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\RoomCategory;
use Carbon\Carbon;
use Auth;
use DB;

class DashboardController extends Controller
{
    private $url = 'dashboard';
    private $office_hour_start = '';
    private $after_office_hour_end = '';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->office_hour_start = ParameterSetting::where('name', 'office_hour_start')->first();
        $this->after_office_hour_end = ParameterSetting::where('name', 'after_office_hour_end')->first();
    }
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

        $data['months'] = array(
            [
                'name' => 'January', 'number' => '01'
            ],

            [
                'name' => 'February', 'number' => '02'
            ],

            [
                'name' => 'March', 'number' => '03'
            ],

            [
                'name' => 'April', 'number' => '04'
            ],

            [
                'name' => 'May', 'number' => '05'
            ],

            [
                'name' => 'June', 'number' => '06'
            ],

            [
                'name' => 'July', 'number' => '07'
            ],

            [
                'name' => 'August', 'number' => '08'
            ],

            [
                'name' => 'September', 'number' => '09'
            ],
            [
                'name' => 'October', 'number' => '10'
            ],
            [
                'name' => 'November', 'number' => '11'
            ],
            [
                'name' => 'December', 'number' => '12'
            ]
        );

        $active_status_id = array(1, 2, 4);

        $data['date'] = Carbon::now()->isoFormat('MM');
        $data['this_year'] = date('Y');
        $data['location_id'] = Location::all();
        $data['total_customer'] = Customer::count();
        $total_active_customer = DB::table('booking_details')
            ->join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->where('booking_details.year', date('Y'))
            ->where('booking_details.month', date('m'))
            ->whereIn('bookings.status_id', $active_status_id)
            ->select(DB::raw('count(*) as count, bookings.customer_id'))
            ->groupBy('bookings.customer_id')
            ->get();

        $data['total_active_customer'] = sizeof($total_active_customer);


        return view('pages.dashboard.index', $data);
    }

    public function getDataBooking(Request $request)
    {
        $year = $request['year'];
        $months = $request['months'];
        $location_id = $request['location_id'];
        $room_category_id = $request['room_category_id'];
        $show = $request['show'];
        $list = array();
        $hour = array();
        $day = array();
        $month = array();
        $first_day_of_month = date($year . '-' . $months . '-01');
        $last_day_of_month = date('t', strtotime($first_day_of_month));
        $month_name = date('F', strtotime($first_day_of_month));
        $rooms = Room::select('rooms.*')
            ->leftjoin('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->leftjoin('room_categories', 'r_c_and_room.room_category_id', 'room_categories.id')
            ->where('room_categories.id', $room_category_id)
            ->where('location_id', $location_id)
            ->get();

        if ($show == 'hourly') {
            $h = $this->office_hour_start->int_value;
            $total_length = $this->after_office_hour_end->int_value - $this->office_hour_start->int_value;
            for ($i = 0; $i < $total_length; $i++) {
                $selected_hour = $h;
                $next_hour = $h + 1;
                if ($selected_hour < 10) {
                    $selected_hour = '0' . $selected_hour;
                }
                if ($next_hour < 10) {
                    $next_hour = '0' . $next_hour;
                }
                $full_date_detail = $selected_hour . ":00 - " . $next_hour . ":00";
                array_push($hour, $full_date_detail);
                foreach ($rooms as $no => $detail) {
                    $detail_data = '';
                    $package_ids = array();
                    foreach ($detail->package as $list_of_package) {
                        array_push($package_ids, $list_of_package->id);
                    }
                    $package_booking_detail = BookingDetail::select('booking_details.*', 'customers.name as customer_name')
                        ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                        ->join('customers', 'bookings.customer_id', 'customers.id')
                        ->whereIn('booking_details.package_id', $package_ids)
                        ->where('booking_details.start_date', date('Y-m-d'))
                        ->where('bookings.price_type', 'hourly')
                        ->get();

                    $room_booking_detail = BookingDetail::select('booking_details.*', 'customers.name as customer_name')
                        ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                        ->join('customers', 'bookings.customer_id', 'customers.id')
                        ->where('booking_details.room_id', $detail->id)
                        ->where('booking_details.start_date', date('Y-m-d'))
                        ->where('bookings.price_type', 'hourly')
                        ->get();

                    $merged = $package_booking_detail->merge($room_booking_detail);

                    $booking_detail = $merged->all();

                    foreach ($booking_detail as $seq => $data) {
                        switch ($room_category_id) {
                            case 1:
                                $url = url("/serviced_office/{$data->booking_id}");
                                break;
                            case 2:
                                $url = url("/meeting_room/{$data->booking_id}");
                                break;
                            case 3:
                                $url = url("/coworking/{$data->booking_id}");
                                break;
                            case 4:
                                $url = url("/hotel/{$data->booking_id}");
                                break;
                            case 5:
                                $url = url("/regular_office/{$data->booking_id}");
                                break;
                        }
                        $detail_data .= '<a target="_blank" href="' . $url . '">' . $data->customer_name . '</a>';
                        if ($seq + 1 < sizeof($booking_detail)) {
                            $detail_data .= $detail_data . ' / ';
                        }
                    }
                    $list[$i][$no] = $detail_data;
                }
                $h++;
            }
        } else if ($show == 'daily') {
            for ($d = 1; $d <= intval($last_day_of_month); $d++) {
                $selected_date = $d;
                if ($selected_date < 10) {
                    $selected_date = '0' . $selected_date;
                }
                $full_date_detail = date("l, d", strtotime($year . '-' . $months . '-' . $selected_date));
                array_push($day, $full_date_detail);
                foreach ($rooms as $no => $detail) {
                    $detail_data = '';
                    $package_ids = array();
                    foreach ($detail->package as $list_of_package) {
                        array_push($package_ids, $list_of_package->id);
                    }
                    $package_booking_detail = BookingDetail::select('booking_details.*', 'customers.name as customer_name')
                        ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                        ->join('customers', 'bookings.customer_id', 'customers.id')
                        ->whereIn('booking_details.package_id', $package_ids)
                        ->where('booking_details.start_date', '<=', date($year . '-' . $months . '-' . $selected_date))
                        ->where('booking_details.end_date', '>=', date($year . '-' . $months . '-' . $selected_date))
                        ->where('bookings.price_type', 'daily')
                        ->get();

                    $room_booking_detail = BookingDetail::select('booking_details.*', 'customers.name as customer_name')
                        ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                        ->join('customers', 'bookings.customer_id', 'customers.id')
                        ->where('booking_details.room_id', $detail->id)
                        ->where('booking_details.start_date', '<=', date($year . '-' . $months . '-' . $selected_date))
                        ->where('booking_details.end_date', '>=', date($year . '-' . $months . '-' . $selected_date))
                        ->get();

                    $merged = $package_booking_detail->merge($room_booking_detail);

                    $booking_detail = $merged->all();

                    foreach ($booking_detail as $seq => $data) {
                        switch ($room_category_id) {
                            case 1:
                                $url = url("/serviced_office/{$data->booking_id}");
                                break;
                            case 2:
                                $url = url("/meeting_room/{$data->booking_id}");
                                break;
                            case 3:
                                $url = url("/coworking/{$data->booking_id}");
                                break;
                            case 4:
                                $url = url("/hotel/{$data->booking_id}");
                                break;
                            case 5:
                                $url = url("/regular_office/{$data->booking_id}");
                                break;
                        }
                        $detail_data .= '<a target="_blank" href="' . $url . '">' . $data->customer_name . '</a>';
                        if ($seq + 1 < sizeof($booking_detail)) {
                            $detail_data .= $detail_data . ' / ';
                        }
                    }

                    $list[$d - 1][$no] = $detail_data;
                }
            }
        } else if ($show == 'monthly') {
            for ($m = 1; $m <= 12; $m++) {
                $selected_month = $m;
                if ($selected_month < 10) {
                    $selected_month = '0' . $selected_month;
                }
                $full_date_detail = date("F", strtotime($year . '-' . $selected_month . '-01'));
                array_push($month, $full_date_detail);
                foreach ($rooms as $no => $detail) {
                    $detail_data = '';
                    $package_ids = array();
                    foreach ($detail->package as $list_of_package) {
                        array_push($package_ids, $list_of_package->id);
                    }
                    $package_booking_detail = BookingDetail::select('booking_details.*', 'customers.name as customer_name')
                        ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                        ->join('customers', 'bookings.customer_id', 'customers.id')
                        ->whereIn('booking_details.package_id', $package_ids)
                        ->where('booking_details.month', $m)
                        ->where('booking_details.year', $year)
                        ->where('bookings.price_type', 'hourly')
                        ->get();

                    $room_booking_detail = BookingDetail::select('booking_details.*', 'customers.name as customer_name')
                        ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                        ->join('customers', 'bookings.customer_id', 'customers.id')
                        ->where('booking_details.room_id', $detail->id)
                        ->where('booking_details.month', $m)
                        ->where('booking_details.year', $year)
                        ->get();

                    $merged = $package_booking_detail->merge($room_booking_detail);

                    $booking_detail = $merged->all();

                    foreach ($booking_detail as $seq => $data) {
                        switch ($room_category_id) {
                            case 1:
                                $url = url("/serviced_office/{$data->booking_id}");
                                break;
                            case 2:
                                $url = url("/meeting_room/{$data->booking_id}");
                                break;
                            case 3:
                                $url = url("/coworking/{$data->booking_id}");
                                break;
                            case 4:
                                $url = url("/hotel/{$data->booking_id}");
                                break;
                            case 5:
                                $url = url("/regular_office/{$data->booking_id}");
                                break;
                        }
                        $detail_data .= '<a target="_blank" href="' . $url . '">' . $data->customer_name . '</a>';
                        if ($seq + 1 < sizeof($booking_detail)) {
                            $detail_data .= $detail_data . ' / ';
                        }
                    }
                    $list[$m - 1][$no] = $detail_data;
                }
            }
        } else { }

        $data['list'] = $list;
        $data['hours'] = $hour;
        $data['days'] = $day;
        $data['months'] = $month;
        $data['rooms'] = $rooms;
        $data['month_name'] = $month_name;
        $data['year'] = $year;

        return $data;
    }

    public function getTotalBookingPerMonth(Request $request)
    {
        $active_status_id = array(1, 2, 4);
        $month = array();
        $list = array();
        $location_id = $request['location_id'];
        $year = $request['year'];
        $type = $request['type'];
        $room_category_id = $request['room_category_id'];

        if ($type == 'product') {
            for ($m = 1; $m <= 12; $m++) {
                $selected_month = $m;
                if ($selected_month < 10) {
                    $selected_month = '0' . $selected_month;
                }
                $full_date_detail = date("F", strtotime($year . '-' . $selected_month . '-01'));
                $first_day_of_month = date("Y-m-d", strtotime($year . '-' . $selected_month . '-01'));
                $last_day_of_month = date("Y-m-t", strtotime($first_day_of_month));
                array_push($month, $full_date_detail);

                $total_booking = Booking::where('type', $type)
                    ->where('start_date', '>=', $first_day_of_month)
                    ->where('start_date', '<=', $last_day_of_month)
                    ->where('location_id', $location_id)
                    ->whereIn('status_id', $active_status_id)
                    ->count();

                array_push($list, $total_booking);
            }
        } else if ($type == 'room') {
            for ($m = 1; $m <= 12; $m++) {
                $selected_month = $m;
                if ($selected_month < 10) {
                    $selected_month = '0' . $selected_month;
                }
                $full_date_detail = date("F", strtotime($year . '-' . $selected_month . '-01'));
                $first_day_of_month = date("Y-m-d", strtotime($year . '-' . $selected_month . '-01'));
                $last_day_of_month = date("Y-m-t", strtotime($first_day_of_month));
                array_push($month, $full_date_detail);

                $total_booking = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                    ->where('bookings.room_category_id', $room_category_id)
                    ->where('bookings.type', $type)
                    ->where('bookings.start_date', '>=', $first_day_of_month)
                    ->where('bookings.start_date', '<=', $last_day_of_month)
                    ->where('bookings.location_id', $location_id)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->count();

                array_push($list, $total_booking);
            }
        }

        $data['list'] = $list;
        $data['months'] = $month;
        $data['year'] = $year;

        return $data;
    }

    public function getOccupancyGraph(Request $request)
    {
        $year = $request['year'];
        $month = $request['months'];
        $location_id = $request['location_id'];
        $room_category_id = $request['room_category_id'];

        $return['total_sqm'] = Room::join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->where('r_c_and_room.room_category_id', 1)
            ->where('location_id', $location_id)
            ->sum('rooms.sqm');

        $return['occupied_sqm'] = BookingDetail::join('rooms', 'rooms.id', 'booking_details.room_id')
            ->join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->where('bookings.room_category_id', $room_category_id)
            ->where('bookings.location_id', $location_id)
            ->where('booking_details.year', $year)
            ->where('booking_details.month', $month)
            ->sum('rooms.sqm');

        $return['availability_sqm'] = $return['total_sqm'] - $return['occupied_sqm'];

        if ($return['total_sqm'] > 0) {
            $return['occupied_per'] = ($return['occupied_sqm'] / $return['total_sqm']) * 100;
        } else {
            $return['occupied_per'] = 0;
        }

        $return['availability_per'] = 100 - $return['occupied_per'];


        $return['total_sqm'] = round($return['total_sqm']);
        $return['occupied_sqm'] = round($return['occupied_sqm']);
        $return['availability_sqm'] = round($return['availability_sqm']);
        $return['occupied_per'] = round($return['occupied_per']);
        $return['availability_per'] = round($return['availability_per']);

        return $return;
    }

    public function getTotalCustomer(Request $request)
    {
        $return = null;
        $active_status_id = array(1, 2, 4);

        $location_id = $request['location_id'];
        $year = $request['year'];
        $month = $request['months'];

        // $total_vo_customer = DB::table('booking_details')
        //     ->join('bookings', 'bookings.id', 'booking_details.booking_id')
        //     ->where('bookings.type', 'product')
        //     ->where('bookings.location_id', $location_id)
        //     ->where('booking_details.year', $year)
        //     ->where('booking_details.month', $month)
        //     ->whereIn('bookings.status_id', $active_status_id)
        //     ->count();

        $total_vo_customer = DB::table('booking_details')
            ->join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->where('bookings.type', 'product')
            ->where('bookings.location_id', $location_id)
            ->where('booking_details.year', $year)
            ->where('booking_details.month', $month)
            ->whereIn('bookings.status_id', $active_status_id)
            ->select(DB::raw('count(*) as count, bookings.customer_id'))
            ->groupBy('bookings.customer_id')
            ->get();

        // $total_so_customer = DB::table('booking_details')
        //     ->join('bookings', 'bookings.id', 'booking_details.booking_id')
        //     ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
        //     ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
        //     ->where('room_categories.code', 'SO')
        //     ->where('bookings.type', 'room')
        //     ->where('bookings.location_id', $location_id)
        //     ->where('booking_details.year', $year)
        //     ->where('booking_details.month', $month)
        //     ->whereIn('bookings.status_id', $active_status_id)
        //     ->count();
        
        $total_so_customer = DB::table('booking_details')
            ->join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
            ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
            ->where('room_categories.code', 'SO')
            ->where('bookings.type', 'room')
            ->where('bookings.location_id', $location_id)
            ->where('booking_details.year', $year)
            ->where('booking_details.month', $month)
            ->whereIn('bookings.status_id', $active_status_id)
            ->select(DB::raw('count(*) as count, bookings.customer_id'))
            ->groupBy('bookings.customer_id')
            ->get();
        
        $total_ws_customer = DB::table('booking_details')
            ->join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
            ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
            ->where('room_categories.code', 'CW')
            ->where('bookings.type', 'room')
            ->where('bookings.location_id', $location_id)
            ->where('booking_details.year', $year)
            ->where('booking_details.month', $month)
            ->whereIn('bookings.status_id', $active_status_id)
            ->select(DB::raw('count(*) as count, bookings.customer_id'))
            ->groupBy('bookings.customer_id')
            ->get();

        $return['total_vo_customer'] = sizeof($total_vo_customer);
        $return['total_so_customer'] = sizeof($total_so_customer);
        $return['total_ws_customer'] = sizeof($total_ws_customer);

        return $return;
    }
}
