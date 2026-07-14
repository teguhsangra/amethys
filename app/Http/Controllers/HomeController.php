<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParameterSetting;
use App\Models\Module;
use App\Models\Location;
use App\Models\Company;
use App\Models\Room;
use App\Models\Package;
use App\Models\Inquiry;
use App\Models\BookingDetail;
use App\Models\Booking;
use App\Models\Proforma;
use App\Models\Invoice;
use App\Models\DedicatedPhone;
use App\User;
use Auth;
use DB;

class HomeController extends Controller
{
    private $add_or_minus_day;
    private $date_format;
    private $time_format;
    private $total_halfday_term;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $parameter_of_add_or_minus_day = ParameterSetting::where('name', 'add_or_minus_day')->first();
        $parameter_of_date_format = ParameterSetting::where('name', 'date_format')->first();
        $parameter_of_time_format = ParameterSetting::where('name', 'time_format')->first();
        $parameter_of_total_halfday_term = ParameterSetting::where('name', 'total_halfday_term')->first();
        $this->add_or_minus_day = $parameter_of_add_or_minus_day->int_value;
        $this->date_format = $parameter_of_date_format->string_value;
        $this->time_format = $parameter_of_time_format->string_value;
        $this->total_halfday_term = $parameter_of_total_halfday_term->int_value;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function setup_periode(Request $request)
    {
        $driven_by = $request['driven_by'];
        $price_type = $request['price_type'];
        $start_date = date('Y-m-d', strtotime($request['start_date']));
        $length_of_term = $request['length_of_term'];
        $end_date = date('Y-m-d', strtotime($request['end_date']));
        $start_time = $request['start_time'];
        $end_time = $request['end_time'];
        $start_date_counted = $request['start_date_counted'];
        $date_format = $request['date_format'];
        if(!empty($date_format)){
            $this->date_format = $date_format;
        }

        $message = 'not_complete';

        switch ($driven_by) {
            case "start_date":
                switch ($price_type) {
                    case "hourly":
                        $end_date = date($this->date_format, strtotime($start_date));
                        $message = 'complete';
                        break;
                    case "daily":
                        if ($length_of_term != null) {
                            $end_date = date($this->date_format, strtotime("+" . $length_of_term . " days", strtotime($start_date)));
                            if ($start_date_counted == "Y") {
                                $end_date = date($this->date_format, strtotime("-1 days", strtotime($end_date)));
                            }
                            $message = 'complete';
                        }
                        break;
                    case "monthly":
                        if ($length_of_term != null) {
                            $end_date = date($this->date_format, strtotime("+" . $length_of_term . " months", strtotime($start_date)));
                            $end_date = date($this->date_format, strtotime("-" . $this->add_or_minus_day . " days", strtotime($end_date)));
                            $message = 'complete';
                        }
                        break;
                    case "yearly":
                        if ($length_of_term != null) {
                            $end_date = date($this->date_format, strtotime("+" . $length_of_term . " years", strtotime($start_date)));
                            $end_date = date($this->date_format, strtotime("-" . $this->add_or_minus_day . " days", strtotime($end_date)));
                            $message = 'complete';
                        }
                        break;
                    case "halfday":
                        $end_date = date($this->date_format, strtotime($start_date));
                        $total_hours = 3600 * $this->total_halfday_term;
                        $full_date = date("Y-m-d " . $start_time);
                        $end_time = date($this->time_format, strtotime($full_date) + $total_hours);
                        $length_of_term = $this->total_halfday_term;
                        $message = 'complete';
                        break;
                }
                break;
            case "length_of_term":
                switch ($price_type) {
                    case "hourly":
                        if ($start_time != null || $end_time != null) {
                            $end_date = date($this->date_format, strtotime($start_date));
                            $total_hours = 3600 * $length_of_term;
                            if ($start_time != null) {
                                $full_date = date("Y-m-d " . $start_time);
                                $end_time = date($this->time_format, strtotime($full_date) + $total_hours);
                            }
                            if ($end_time != null) {
                                $full_date = date("Y-m-d " . $end_time);
                                $start_time = date($this->time_format, strtotime($full_date) - $total_hours);
                            }
                            $message = 'complete';
                        }
                        break;
                    case "daily":
                        if ($start_date != null || $end_date != null) {
                            if ($start_date != null) {
                                $end_date = date($this->date_format, strtotime("+" . $length_of_term . " days", strtotime($start_date)));
                                if ($start_date_counted == "Y") {
                                    $end_date = date($this->date_format, strtotime("-1 days", strtotime($end_date)));
                                }
                            }
                            if ($end_date != null && $start_date == null) {
                                $start_date = date($this->date_format, strtotime("-" . $length_of_term . " days", strtotime($end_date)));
                                if ($start_date_counted == "Y") {
                                    $end_date = date($this->date_format, strtotime("+1 days", strtotime($end_date)));
                                }
                            }
                            $message = 'complete';
                        }
                        break;
                    case "monthly":
                        if ($start_date != null || $end_date != null) {
                            if ($start_date != null) {
                                $end_date = date($this->date_format, strtotime("+" . $length_of_term . " months", strtotime($start_date)));
                                $end_date = date($this->date_format, strtotime("-" . $this->add_or_minus_day . " days", strtotime($end_date)));
                            }
                            if ($end_date != null) {
                                $start_date = date($this->date_format, strtotime("-" . $length_of_term . " months", strtotime($end_date)));
                                $start_date = date($this->date_format, strtotime("+" . $this->add_or_minus_day . " days", strtotime($start_date)));
                            }
                            $message = 'complete';
                        }
                        break;
                    case "yearly":
                        if ($start_date != null || $end_date != null) {
                            if ($start_date != null) {
                                $end_date = date($this->date_format, strtotime("+" . $length_of_term . " years", strtotime($start_date)));
                                $end_date = date($this->date_format, strtotime("-" . $this->add_or_minus_day . " days", strtotime($end_date)));
                            }
                            if ($end_date != null) {
                                $start_date = date($this->date_format, strtotime("-" . $length_of_term . " years", strtotime($end_date)));
                                $start_date = date($this->date_format, strtotime("+" . $this->add_or_minus_day . " days", strtotime($start_date)));
                            }
                            $message = 'complete';
                        }
                        break;
                }
                break;
            case "end_date":
                switch ($price_type) {
                    case "hourly":
                        $start_date = date($this->date_format, strtotime($end_date));
                        $message = 'complete';
                        break;
                    case "daily":
                        if ($length_of_term != null) {
                            $start_date = date($this->date_format, strtotime("-" . $length_of_term . " days", strtotime($end_date)));
                            if ($start_date_counted == "Y") {
                                $end_date = date($this->date_format, strtotime("+1 days", strtotime($end_date)));
                            }
                            $message = 'complete';
                        }
                        break;
                    case "monthly":
                        if ($length_of_term != null) {
                            $start_date = date($this->date_format, strtotime("-" . $length_of_term . " months", strtotime($end_date)));
                            $start_date = date($this->date_format, strtotime("+" . $this->add_or_minus_day . " days", strtotime($start_date)));
                            $message = 'complete';
                        }
                        break;
                    case "yearly":
                        if ($length_of_term != null) {
                            $start_date = date($this->date_format, strtotime("-" . $length_of_term . " years", strtotime($end_date)));
                            $start_date = date($this->date_format, strtotime("+" . $this->add_or_minus_day . " days", strtotime($start_date)));
                            $message = 'complete';
                        }
                        break;
                }
                break;
            case "start_time":
                if ($length_of_term != null && $price_type == 'hourly') {
                    $total_hours = 3600 * $length_of_term;
                    $full_date = date("Y-m-d " . $start_time);
                    $end_time = date($this->time_format, strtotime($full_date) + $total_hours);
                    $message = 'complete';
                }

                if ($price_type == 'halfday') {
                    $end_date = date($this->date_format, strtotime($start_date));
                    $total_hours = 3600 * $this->total_halfday_term;
                    $full_date = date("Y-m-d " . $start_time);
                    $end_time = date($this->time_format, strtotime($full_date) + $total_hours);
                    $length_of_term = $this->total_halfday_term;
                    $message = 'complete';
                }
                break;
            case "end_time":
                if ($length_of_term != null && $price_type == 'hourly') {
                    $total_hours = 3600 * $length_of_term;
                    $full_date = date("Y-m-d " . $end_time);
                    $start_time = date($this->time_format, strtotime($full_date) - $total_hours);
                    $message = 'complete';
                }
                break;
        }
        $start_date = date($this->date_format, strtotime($start_date));
        $end_date = date($this->date_format, strtotime($end_date));

        $return['message'] = $message;
        $return['start_date'] = $start_date;
        $return['length_of_term'] = $length_of_term;
        $return['end_date'] = $end_date;
        $return['start_time'] = $start_time;
        $return['end_time'] = $end_time;
        return $return;
    }

    public static function check_exist_folder($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    public function is_base64($str)
    {
        if (base64_encode(base64_decode($str, true)) === $str) {
            return true;
        } else {
            return false;
        }
    }

    public static function setMonth($month)
    {
        $month_name = '';
        switch ($month) {
            case 1:
                $month_name = 'JAN';
                break;
            case 2:
                $month_name = 'FEB';
                break;
            case 3:
                $month_name = 'MAR';
                break;
            case 4:
                $month_name = 'APR';
                break;
            case 5:
                $month_name = 'MAY';
                break;
            case 6:
                $month_name = 'JUN';
                break;
            case 7:
                $month_name = 'JUL';
                break;
            case 8:
                $month_name = 'AUG';
                break;
            case 9:
                $month_name = 'SEP';
                break;
            case 10:
                $month_name = 'OCT';
                break;
            case 11:
                $month_name = 'NOV';
                break;
            case 12:
                $month_name = 'DEC';
                break;
            default:
                $month_name = "";
                break;
        }
        return $month_name;
    }

    public static function setRomawi($number)
    {
        $romawi = "";
        switch ($number) {
            case 1:
                $romawi = "I";
                break;
            case 2:
                $romawi = "II";
                break;
            case 3:
                $romawi = "III";
                break;
            case 4:
                $romawi = "IV";
                break;
            case 5:
                $romawi = "V";
                break;
            case 6:
                $romawi = "VI";
                break;
            case 7:
                $romawi = "VII";
                break;
            case 8:
                $romawi = "VIII";
                break;
            case 9:
                $romawi = "IX";
                break;
            case 10:
                $romawi = "X";
                break;
            case 11:
                $romawi = "XI";
                break;
            case 12:
                $romawi = "XII";
                break;

            default:
                $romawi = "";
                break;
        }
        return $romawi;
    }

    public static function setZero($number)
    {
        $number_format = "";

        if ($number < 10) {
            $number_format = "000" . $number;
        } else if ($number >= 10 && $number < 100) {
            $number_format = "00" . $number;
        } else if ($number >= 100 && $number < 1000) {
            $number_format = "0" . $number;
        } else if ($number >= 1000 && $number < 10000) {
            $number_format = $number;
        }

        return $number_format;
    }

    public static function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }

    public static function getAccess($link, $user_id = null)
    {
        $module = Module::where('link', $link)->first();

        $a_g_and_module = null;

        if($module != null){
            if ($user_id == null) {
                $a_g_and_module = DB::table('a_g_and_module')
                    ->where('access_group_id', Auth::user()->access_group_id)
                    ->where('module_id', $module->id)
                    ->first();
            } else {
                $user = User::findOrFail($user_id);
                $a_g_and_module = DB::table('a_g_and_module')
                    ->where('access_group_id', $user->access_group_id)
                    ->where('module_id', $module->id)
                    ->first();
            }
        }

        return $a_g_and_module;
    }

    public static function getMasterCode($table_name, $prefix_name)
    {
        $sequence = 0;

        $total_data = DB::table($table_name)->count();

        $sequence = $total_data + 1;
        $check_unique_code = false;

        while (!$check_unique_code) {
            $code = $prefix_name . '-' . self::setZero($sequence);

            $get_detail_data = DB::table($table_name)->where('code', $code)->first();

            if ($get_detail_data == null) {
                $check_unique_code = true;
            } else {
                $sequence++;
                $check_unique_code = false;
            }
        }

        return $code;
    }

    public static function getTransactionCode($table_name, $prefix_name, $location_id = null, $company_id = null, $other_code = null, $set_sequence = null)
    {
        $sequence = 0;
        $total_data = 0;

        // No Filter
        if ($location_id == null && $company_id == null && $other_code == null) {
            $total_data = DB::table($table_name)
                ->where('created_at', '>=', date('Y-01-01 00:00:00'))
                ->where('created_at', '<=', date('Y-12-31 23:59:59'))
                ->count();
        }
        // Only Location ID
        if ($location_id != null && $company_id == null && $other_code == null) {
            $total_data = DB::table($table_name)
                ->where('location_id', $location_id)
                ->where('created_at', '>=', date('Y-01-01 00:00:00'))
                ->where('created_at', '<=', date('Y-12-31 23:59:59'))
                ->count();
        }
        // Only Company ID
        if ($location_id == null && $company_id != null && $other_code == null) {
            $total_data = DB::table($table_name)
                ->where('created_at', '>=', date('Y-01-01 00:00:00'))
                ->where('created_at', '<=', date('Y-12-31 23:59:59'))
                ->where('company_id', $company_id)
                ->count();
        }
        // Only Other Code
        if ($location_id == null && $company_id == null && $other_code != null) {
            $total_data = DB::table($table_name)
                ->where('created_at', '>=', date('Y-01-01 00:00:00'))
                ->where('created_at', '<=', date('Y-12-31 23:59:59'))
                ->where('other_code', $other_code)
                ->count();
        }
        // Location ID + Company ID
        if ($location_id != null && $company_id != null && $other_code == null) {
            $total_data = DB::table($table_name)
                ->where('created_at', '>=', date('Y-01-01 00:00:00'))
                ->where('created_at', '<=', date('Y-12-31 23:59:59'))
                ->where('location_id', $location_id)
                ->where('company_id', $company_id)
                ->count();
        }
        // Location ID + Other Code
        if ($location_id != null && $company_id == null && $other_code != null) {
            $total_data = DB::table($table_name)
                ->where('created_at', '>=', date('Y-01-01 00:00:00'))
                ->where('created_at', '<=', date('Y-12-31 23:59:59'))
                ->where('location_id', $location_id)
                ->where('other_code', $other_code)
                ->count();
        }
        // Company ID + Other Code
        if ($location_id == null && $company_id != null && $other_code != null) {
            $total_data = DB::table($table_name)
                ->where('created_at', '>=', date('Y-01-01 00:00:00'))
                ->where('created_at', '<=', date('Y-12-31 23:59:59'))
                ->where('company_id', $company_id)
                ->where('other_code', $other_code)
                ->count();
        }
        // Location ID + Company ID + Other Code
        if ($location_id != null && $company_id != null && $other_code != null) {
            $total_data = DB::table($table_name)
                ->where('created_at', '>=', date('Y-01-01 00:00:00'))
                ->where('created_at', '<=', date('Y-12-31 23:59:59'))
                ->where('location_id', $location_id)
                ->where('company_id', $company_id)
                ->where('other_code', $other_code)
                ->count();
        }

        $sequence = $total_data + 1;
        if ($set_sequence != null) {
            $sequence = $set_sequence;
        }
        $check_unique_code = false;

        while (!$check_unique_code) {
            $code = $prefix_name . '-' . self::setZero($sequence);
            // No Filter
            if ($location_id == null && $company_id == null && $other_code == null) {
                $code = $prefix_name . '/' . date("Y") . '/' . self::setRomawi((int) (date("m"))) . '/' . self::setZero($sequence);
            }
            // Only Location ID
            if ($location_id != null && $company_id == null && $other_code == null) {
                $location = Location::findOrFail($location_id);
                $code = $prefix_name . '/' . $location->code . '/' . date("Y") . '/' . self::setRomawi((int) (date("m"))) . '/' . self::setZero($sequence);
            }
            // Only Company ID
            if ($location_id == null && $company_id != null && $other_code == null) {
                $company = Company::findOrFail($company_id);
                $code = $prefix_name . '/' . $company->code . '/' . date("Y") . '/' . self::setRomawi((int) (date("m"))) . '/' . self::setZero($sequence);
            }
            // Only Other Code
            if ($location_id == null && $company_id == null && $other_code != null) {
                $code = $prefix_name . '/' . $other_code . '/' . date("Y") . '/' . self::setRomawi((int) (date("m"))) . '/' . self::setZero($sequence);
            }
            // Location ID + Company ID
            if ($location_id != null && $company_id != null && $other_code == null) {
                $location = Location::findOrFail($location_id);
                $company = Company::findOrFail($company_id);
                $code = $prefix_name . '/' . $company->code . '/' . $location->code . '/' . date("Y") . '/' . self::setRomawi((int) (date("m"))) . '/' . self::setZero($sequence);
            }
            // Location ID + Other Code
            if ($location_id != null && $company_id == null && $other_code != null) {
                $location = Location::findOrFail($location_id);
                $code = $prefix_name . '/' . $other_code . '/' . $location->code . '/' . date("Y") . '/' . self::setRomawi((int) (date("m"))) . '/' . self::setZero($sequence);
            }
            // Company ID + Other Code
            if ($location_id == null && $company_id != null && $other_code != null) {
                $company = Company::findOrFail($company_id);
                $code = $prefix_name . '/' . $other_code . '/' . $company->code . '/' . date("Y") . '/' . self::setRomawi((int) (date("m"))) . '/' . self::setZero($sequence);
            }
            // Location ID + Company ID + Other Code
            if ($location_id != null && $company_id != null && $other_code != null) {
                $location = Location::findOrFail($location_id);
                $company = Company::findOrFail($company_id);
                $code = $prefix_name . '/' . $other_code . '/' . $company->code . '/' . $location->code . '/' . date("Y") . '/' . self::setRomawi((int) (date("m"))) . '/' . self::setZero($sequence);
            }

            $get_detail_data = DB::table($table_name)->where('code', $code)->first();

            if ($get_detail_data == null) {
                $check_unique_code = true;
            } else {
                $sequence++;
                $check_unique_code = false;
            }
        }

        return $code;
    }

    public function check_availability(Request $request)
    {
        $active_status_id = array(1, 2, 4);
        $total_booking = 0;

        $return['available'] = 'true';
        $return['error_message'] = '';
        
        $price_type = null;
        if(!empty($request['price_type'])){
            $price_type = $request['price_type'];
        }

        $type = $request['type'];
        $array_room_id = json_decode($request['array_room_id']);
        $start_date = date('Y-m-d', strtotime($request['start_date']));
        $end_date = date('Y-m-d', strtotime($request['end_date']));
        $start_time = $request['start_time'];
        $end_time = $request['end_time'];
        $package_id = $request['package_id'];
        $booking_id = $request['booking_id'];
        $customer_id = $request['customer_id'];

        $package = Package::where('id', $package_id)->first();

        if($package == null){
            // Do Nothing
        }else{
            $array_room_id = array();
            foreach($package->room as $room){
                array_push($array_room_id, $room->id);
            }
            
            // First Package Checking
            if($total_booking == 0){
                if ($booking_id != null) {
                    $total_booking = Booking::join('booking_and_package', 'booking_and_package.booking_id', 'bookings.id')
                        ->where('booking_and_package.start_date', '<=', $end_date)
                        ->where('booking_and_package.end_date', '>=', $start_date)
                        ->where('type', 'package')
                        ->where('booking_and_package.booking_id', '!=', $booking_id)
                        ->whereIn('status_id', $active_status_id)
                        ->where('booking_and_package.package_id', $package_id)
                        ->count();
                } else {
                    $total_booking = Booking::join('booking_and_package', 'booking_and_package.booking_id', 'bookings.id')
                        ->where('booking_and_package.start_date', '<=', $end_date)
                        ->where('booking_and_package.end_date', '>=', $start_date)
                        ->where('type', 'package')
                        ->whereIn('status_id', $active_status_id)
                        ->where('booking_and_package.package_id', $package_id)
                        ->count();
                }
            }

            // Second Package Checking
            if($total_booking == 0){
                if ($booking_id != null) {
                    $total_booking = Booking::join('booking_and_package', 'booking_and_package.booking_id', 'bookings.id')
                        ->where('booking_and_package.start_date', $end_date)
                        ->where('booking_and_package.start_time', '<', $end_time)
                        ->where('type', 'package')
                        ->where('booking_and_package.booking_id', '!=', $request['booking_id'])
                        ->whereIn('status_id', $active_status_id)
                        ->where('booking_and_package.package_id', $package_id)
                        ->count();
                } else {
                    $total_booking = Booking::join('booking_and_package', 'booking_and_package.booking_id', 'bookings.id')
                        ->where('booking_and_package.start_date', $end_date)
                        ->where('booking_and_package.start_time', '<', $end_time)
                        ->where('type', 'package')
                        ->whereIn('status_id', $active_status_id)
                        ->where('booking_and_package.package_id', $package_id)
                        ->count();
                }
            }

            // Third Room Checking
            if($total_booking == 0){
                if ($booking_id != null) {
                    $total_booking = Booking::join('booking_and_package', 'booking_and_package.booking_id', 'bookings.id')
                        ->where('booking_and_package.start_date', $end_date)
                        ->where('booking_and_package.end_time', '>', $start_time)
                        ->where('type', 'package')
                        ->where('booking_and_package.booking_id', '!=', $request['booking_id'])
                        ->whereIn('status_id', $active_status_id)
                        ->where('booking_and_package.package_id', $package_id)
                        ->count();
                } else {
                    $total_booking = Booking::join('booking_and_package', 'booking_and_package.booking_id', 'bookings.id')
                        ->where('booking_and_package.start_date', $end_date)
                        ->where('booking_and_package.end_time', '>', $start_time)
                        ->where('type', 'package')
                        ->whereIn('status_id', $active_status_id)
                        ->where('booking_and_package.package_id', $package_id)
                        ->count();
                }
            }
        }

        if(sizeof($array_room_id) > 0){
            // First Room Checking
            if($total_booking == 0 && $price_type != 'hourly'){
                if ($booking_id != null) {
                    $total_booking = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                        ->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $start_date)
                        ->where('type', 'room')
                        ->where('booking_and_room.booking_id', '!=', $booking_id)
                        ->whereIn('status_id', $active_status_id)
                        ->whereIn('booking_and_room.room_id', $array_room_id)
                        ->count();
                } else {
                    $total_booking = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                        ->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $start_date)
                        ->where('type', 'room')
                        ->whereIn('status_id', $active_status_id)
                        ->whereIn('booking_and_room.room_id', $array_room_id)
                        ->count();
                }
            }

            // Second Room Checking
            if($total_booking == 0){
                if ($booking_id != null) {
                    $total_booking = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                        ->where('start_date', $end_date)
                        ->where('start_time', '>', $start_time)
                        ->where('start_time', '<', $end_time)
                        ->where('type', 'room')
                        ->where('booking_and_room.booking_id', '!=', $request['booking_id'])
                        ->whereIn('status_id', $active_status_id)
                        ->whereIn('booking_and_room.room_id', $array_room_id)
                        ->select('booking_and_room.room_id')
                        ->count();
                } else {
                    $total_booking = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                        ->where('start_date', $end_date)
                        ->where('start_time', '>', $start_time)
                        ->where('start_time', '<', $end_time)
                        ->where('type', 'room')
                        ->whereIn('status_id', $active_status_id)
                        ->whereIn('booking_and_room.room_id', $array_room_id)
                        ->select('booking_and_room.room_id')
                        ->count();
                }
            }


            // Third Room Checking
            if($total_booking == 0){
                if ($booking_id != null) {
                    $total_booking = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                        ->where('start_date', $end_date)
                        ->where('end_time', '>', $start_time)
                        ->where('end_time', '<', $end_time)
                        ->where('type', 'room')
                        ->where('booking_and_room.booking_id', '!=', $request['booking_id'])
                        ->whereIn('status_id', $active_status_id)
                        ->whereIn('booking_and_room.room_id', $array_room_id)
                        ->select('booking_and_room.room_id')
                        ->count();
                } else {
                    $total_booking = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                        ->where('start_date', $end_date)
                        ->where('end_time', '>', $start_time)
                        ->where('end_time', '<', $end_time)
                        ->where('type', 'room')
                        ->whereIn('status_id', $active_status_id)
                        ->whereIn('booking_and_room.room_id', $array_room_id)
                        ->select('booking_and_room.room_id')
                        ->count();
                }
            }
        }

        if($type == 'package'){
            if ($total_booking > 0 ) {
                $return['available'] = 'false';
                $return['error_message'] .= '<br>Package ' . $package->name . ' is not available at ' . $start_date . ' to ' . $end_date . ' (' . $start_time . ' - ' . $end_time . ')';
            }
        }else if($type == 'room'){
            if ($total_booking > 0 ) {
                $return['available'] = 'false';
                for ($i = 0; $i < sizeof($array_room_id); $i++) {
                    $room = Room::findOrFail($array_room_id[$i]);
                    $return['error_message'] .= '<br>Room ' . $room->room_number . ' is not available at ' . $start_date . ' to ' . $end_date . ' (' . $start_time . ' - ' . $end_time . ')';
                }
            }
        } else {
            // Do Nothing
        }

        return $return;
    }

    public function getProformaInvoiceByBooking($booking_id)
    {
        $active_status_id = array(2, 4);

        $return['proformas'] = Proforma::where('booking_id', $booking_id)
            ->whereIn('status_id', $active_status_id)
            ->get();

        $return['invoices'] = Invoice::where('booking_id', $booking_id)
            ->whereIn('status_id', $active_status_id)
            ->get();

        return $return;
    }

    public function getProformaInvoiceByOrder($order_id)
    {
        $active_status_id = array(1, 2, 4);

        $return['proformas'] = Proforma::where('order_id', $order_id)
            ->whereIn('status_id', $active_status_id)
            ->get();

        $return['invoices'] = Invoice::where('order_id', $order_id)
            ->whereIn('status_id', $active_status_id)
            ->get();

        return $return;
    }

    public function getProformaInvoiceByInquiry($inquiry_id)
    {
        $active_status_id = array(1, 2, 4);

        $return['proformas'] = Proforma::where('inquiry_id', $inquiry_id)
            ->whereIn('status_id', $active_status_id)
            ->get();

        $return['invoices'] = Invoice::where('inquiry_id', $inquiry_id)
            ->whereIn('status_id', $active_status_id)
            ->get();

        return $return;
    }
}
