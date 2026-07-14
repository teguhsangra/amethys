<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ParameterSetting;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Room;
use App\Models\Notification;
use App\Models\Complimentary;
use App\Models\Inquiry;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\BookingComplimentary;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Deposit;
use App\Models\TechnicalMeeting;
use App\Models\TechnicalMeetingArea;
use App\Models\TechnicalMeetingAreaDetail;
use Validator;
use Redirect;
use Auth;
use DB;

class BookingController extends Controller
{
    public static $parent_ids = array();

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function redirect_inquiry_to_booking(Request $request)
    {
        $inquiry_id = $request['inquiry_id'];
        $redirect_url = 'inquiry';
        if (!empty($inquiry_id)) {
            $inquiry = Inquiry::findOrFail($inquiry_id);
            switch ($inquiry->type) {
                case "product":
                    $redirect_url = 'virtual_office/create?inquiry_id=' . $inquiry_id;
                    break;
                case "package":
                    $redirect_url = 'booking_package/create?inquiry_id=' . $inquiry_id;
                    break;
                case "room":
                    switch ($inquiry->room_category->code) {
                        case "SO":
                            $redirect_url = 'serviced_office/create?inquiry_id=' . $inquiry_id;
                            break;

                        case "RO":
                            $redirect_url = 'regular_office/create?inquiry_id=' . $inquiry_id;
                            break;

                        case "LO":
                            $redirect_url = 'hotel/create?inquiry_id=' . $inquiry_id;
                            break;

                        case "CW":
                            $redirect_url = 'coworking/create?inquiry_id=' . $inquiry_id;
                            break;

                        case "MR":
                            $redirect_url = 'meeting_room/create?inquiry_id=' . $inquiry_id;
                            break;
                    }
                    break;
            }
        }

        return Redirect::to($redirect_url);
    }

    public static function get_parent_of_this_employee($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->parent_id != null) {
            array_push(self::$parent_ids, $employee->parent_id);
            self::get_parent_of_this_employee($employee->parent_id);
        }
    }

    public static function sendApprovalNotification($booking)
    {
        $return = true;

        $array_access_group_id = array();
        $url = 'serviced_office';

        switch ($booking->type) {
            case "room":
                switch ($booking->room_category->code) {
                    case "SO":
                        $url = 'serviced_office';
                        break;
                    case "MR":
                        $url = 'meeting_room';
                        break;
                    case "CW":
                        $url = 'coworking';
                        break;
                    case "LO":
                        $url = 'hotel';
                        break;
                    case "RO":
                        $url = 'regular_office';
                        break;
                }
                break;

            case "product":
                $url = 'virtual_office';
                break;

            case "package":
                $url = 'booking_package';
                break;
        }

        /* Versi Lama
        self::get_parent_of_this_employee($booking->employee_id);
        $employees = Employee::whereIn('id', self::$parent_ids)->get();
        */

        if ($booking->status->name == "open") {
            $a_g_and_module = DB::table('a_g_and_module')
                ->join('modules', 'modules.id', 'a_g_and_module.module_id')
                ->where('a_g_and_module.isExec', 1)
                ->where('modules.link', $url)
                ->get();

            foreach ($a_g_and_module as $detail) {
                array_push($array_access_group_id, $detail->access_group_id);
            }

            $employees = Employee::join('users', 'users.id', 'employees.user_id')
                ->whereIn('users.access_group_id', $array_access_group_id)
                ->get();

            foreach ($employees as $no => $detail) {
                $check_exist_notification = Notification::where('url', $url . '/' . $booking->id . '/edit')
                    ->where('user_id', $detail->user->id)
                    ->where('read_status', 'N')
                    ->first();
                if ($check_exist_notification == null) {
                    if ($booking->status->name == "open") {
                        $notification = new Notification;
                        $notification->user_id = $detail->user->id;
                        $notification->header = "To Do : Approve Agreement " . $booking->customer->name . "-by" . $booking->employee->name;
                        $notification->body = "Booking no = " . $booking->code . " has been created by " . $booking->employee->name . " at" . $booking->created_at;
                        $notification->url = $url . '/' . $booking->id . '/edit';

                        if (!$notification->save()) {
                            $return = false;
                            break;
                        }
                    } else {
                        $notification = new Notification;
                        $notification->user_id = $detail->user->id;
                        $notification->header = $booking->customer->name . ", Approved" . "-by" . $booking->employee->name;
                        $notification->body = "Booking no = " . $booking->code . " has been created by " . $booking->employee->name . " at" . $booking->created_at;
                        $notification->url = $url . '/' . $booking->id . '/edit';

                        if (!$notification->save()) {
                            $return = false;
                            break;
                        }
                    }
                }
            }
        } else if ($booking->status->name == "posted") {
            Notification::where('url', $url . '/' . $booking->id . '/edit')->update(['read_status' => 'Y']);
        } else {
        }

        return $return;
    }

    public static function sendCreatorNotification($booking)
    {
        $return = true;

        $url = 'serviced_office';

        switch ($booking->type) {
            case "room":
                switch ($booking->room_category->code) {
                    case "SO":
                        $url = 'serviced_office';
                        break;
                    case "MR":
                        $url = 'meeting_room';
                        break;
                    case "CW":
                        $url = 'coworking';
                        break;
                    case "LO":
                        $url = 'hotel';
                        break;
                    case "RO":
                        $url = 'regular_office';
                        break;
                }
                break;

            case "product":
                $url = 'virtual_office';
                break;

            case "package":
                $url = 'booking_package';
                break;
        }

        $employees = Employee::join('users', 'users.id', 'employees.user_id')
            ->where('users.name', $booking->draft_by)
            ->get();

        foreach ($employees as $no => $detail) {
            $check_exist_notification = Notification::where('url', $url . '/' . $booking->id . '/edit')
                ->where('user_id', $detail->user->id)
                ->where('read_status', 'N')
                ->first();
            if ($check_exist_notification == null) {
                if ($booking->status->name == "posted") {
                    $notification = new Notification;
                    $notification->user_id = $detail->user->id;
                    $notification->header = $booking->customer->name . ", Approved" . "-by" . $booking->posting_by;
                    $notification->body = "Booking no = " . $booking->code . " has been created by " . $booking->posting_by . " at" . $booking->created_at;
                    $notification->url = $url . '/' . $booking->id;

                    if (!$notification->save()) {
                        $return = false;
                        break;
                    }
                }
            }
        }

        return $return;
    }

    public static function create_booking_detail($booking)
    {
        $return = true;

        BookingDetail::where('booking_id', $booking->id)->delete();

        $parameter_of_tax_percentage = ParameterSetting::where('name', 'tax_percentage')->first();
        $tax_percentage = $parameter_of_tax_percentage->double_value;
        $parameter_of_service_charge = ParameterSetting::where('name', 'service_charge')->first();
        $service_charge = $parameter_of_service_charge->double_value;
        $parameter_of_office_hour_end = ParameterSetting::where('name', 'office_hour_end')->first();
        $office_hour_end = $parameter_of_office_hour_end->int_value;

        $month = date("m", strtotime($booking->start_date));
        $year = date("Y", strtotime($booking->start_date));
        $date = strtotime($booking->start_date);
        $single_discount_price = 0;

        switch ($booking->price_type) {
            case "yearly":
                $total_booking = $booking->length_of_term;
                $length_of_detail = 1;
                $end_date = date("Y-m-d", strtotime("+1 years", $date));
                break;
            case "monthly":
                $total_booking = $booking->length_of_term;
                $length_of_detail = 1;
                $end_date = date("Y-m-d", strtotime("+1 month", $date));
                break;
            case "daily":
                $total_booking = $booking->length_of_term;
                $length_of_detail = 1;
                break;
            case "hourly":
                $total_booking = $booking->length_of_term;
                $length_of_detail = $booking->length_of_term;
                if ($booking->length_of_term_after_office > 0) {
                    $length_of_detail = $length_of_detail - $booking->length_of_term_after_office;
                }
                break;
            default:
                $total_booking = 1;
                $length_of_detail = $booking->length_of_term;
                break;
        }

        $total_discount_price = $booking->discount_price;

        if ($total_discount_price > 0) {
            if ($booking->type == "room") {
                $single_discount_price = ceil($total_discount_price / sizeof($booking->rooms));
            } else if ($booking->type == "package") {
                $single_discount_price = ceil($total_discount_price / sizeof($booking->packages));
            } else if ($booking->type == "product") {
                $single_discount_price = $total_discount_price;
            } else {
                $single_discount_price = 0;
            }
        } else {
            $single_discount_price = 0;
        }

        $single_discount_price = ceil($single_discount_price / $total_booking);

        for ($i = 1; $i <= $total_booking; $i++) {
            $counter_for_start_date = $i - 1;
            $detail_price = 0;
            $detail_tax_price = 0;
            $detail_service_charge = 0;
            $detail_discount_price = 0;

            switch ($booking->price_type) {
                case "yearly":
                    $start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " years", $date));
                    $end_date = date("Y-m-d", strtotime("+" . $i . " years", $date));
                    $end_date = date("Y-m-d", strtotime("-1 day", strtotime($end_date)));
                    $start_time = $booking->start_time;
                    $end_time = $booking->end_time;
                    break;
                case "monthly":
                    $start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " month", $date));
                    $end_date = date("Y-m-d", strtotime("+" . $i . " month", $date));
                    $end_date = date("Y-m-d", strtotime("-1 day", strtotime($end_date)));
                    $start_time = $booking->start_time;
                    $end_time = $booking->end_time;
                    break;
                case "daily":
                    $start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " day", $date));
                    $end_date = date("Y-m-d", strtotime("+" . $i . " day", $date));
                    if ($i == 1) {
                        $start_time = $booking->start_time;
                    }
                    if ($i > 1) {
                        $start_time = '00:00:00';
                    }
                    if ($total_booking >= 1) {
                        $end_time = '23:59:59';
                    }
                    if ($i == $total_booking) {
                        $end_time = $booking->end_time;
                    }
                    if ($booking->start_date_counted == "Y") {
                        $end_date = $start_date;
                    }
                    break;
                case "hourly":
                    $start_date = date('Y-m-d', strtotime($booking->start_date));
                    $end_date = date('Y-m-d', strtotime($booking->end_date));
                    $start_time = $booking->start_time;
                    $end_time = $booking->end_time;

                    if ($booking->length_of_term_after_office > 0) {
                        $end_time = $office_hour_end . ":00:00";
                    }
                    break;
            }

            if ($booking->type == "room") {
                if ($length_of_detail > 0) {
                    foreach ($booking->rooms as $room) {
                    	$detail_price = 0;
                        $detail_tax_price = 0;
                        $detail_service_charge = 0;
                        $detail_discount_price = 0;
                    	
                        $booking_detail = new BookingDetail;

                        $detail_price = $room->pivot->detail_price;
                        if (($total_booking - $i + 1) > $booking->free_term_booking) {
                            if ($booking->usable_discount == "percentage") {
                                $detail_discount_price = $detail_price * ($booking->discount_percentage / 100);
                            } else if ($booking->usable_discount == "price") {
                                if ($total_discount_price > 0) {
                                    $temp = $total_discount_price - $single_discount_price;
                                    if ($temp < 0) {
                                        $detail_discount_price = $total_discount_price;
                                    } else {
                                        $detail_discount_price = $single_discount_price;
                                    }
                                    $total_discount_price = $total_discount_price - $detail_discount_price;
                                }
                            }
                            $detail_price = $detail_price - $detail_discount_price;
                        } else {
                            $detail_price = 0;
                        }

                        if ($booking->complimentary_id != null) {
                            $booking_detail->complimentary_id = $booking->complimentary_id;
                            $booking_detail->detail_use_complimentary = $room->pivot->detail_use_complimentary;
                        }

                        if ($detail_price > 0) {
                            if ($room->has_service_charge == "Y") {
                                $detail_service_charge = round($detail_price * $service_charge);
                            } else {
                                $detail_service_charge = 0;
                            }
                            switch ($booking->tax_status) {
                                case "exclude":
                                    $detail_tax_price = ($detail_price + $detail_service_charge) * $tax_percentage;
                                    break;
                                case "include":
                                    $temp = $detail_price;
                                    $detail_price = round($temp / (1 + $tax_percentage));
                                    $detail_tax_price = $temp - $detail_price;
                                    $detail_service_charge = 0;
                                    $has_service_charge = $room->has_service_charge;

                                    if ($has_service_charge == "Y") {
                                        $temp_1 = $detail_price;
                                        $detail_price = round($temp_1 / (1 + $service_charge));
                                        $detail_service_charge = $temp_1 - $detail_price;
                                    }
                                    break;
                            }
                        }

                        $booking_detail->booking_id = $booking->id;
                        $booking_detail->room_id = $room->id;
                        $booking_detail->month = $month;
                        $booking_detail->year = $year;
                        $booking_detail->detail_sequence = $i;
                        $booking_detail->detail_price = $detail_price;
                        $booking_detail->detail_service_charge = $detail_service_charge;
                        $booking_detail->detail_tax_price = $detail_tax_price;
                        $booking_detail->usable_discount = $booking->usable_discount;
                        $booking_detail->detail_discount_percentage = $booking->discount_percentage;
                        $booking_detail->detail_discount_price = $detail_discount_price;
                        $booking_detail->start_time = $start_time;
                        $booking_detail->end_time = $end_time;
                        $booking_detail->start_date = $start_date;
                        $booking_detail->end_date = $end_date;
                        $booking_detail->length_of_detail = $length_of_detail;

                        if (!$booking_detail->save()) {
                            $return = false;
                            break;
                        }
                    }
                }
            } elseif ($booking->type == "package") {
                $single_discount_price = ceil($single_discount_price / sizeof($booking->packages));
                foreach ($booking->packages as $package) {
                    $package_month = date("m", strtotime($package->pivot->start_date));
                    $package_year = date("Y", strtotime($package->pivot->start_date));
                    $package_date = strtotime($package->pivot->start_date);
                    $total_detail_package = 1;
                    $package_length_of_detail = 1;

                    switch ($package->pivot->price_type) {
                        case "yearly":
                            $total_detail_package = $package->pivot->length_of_term;
                            $package_end_date = date("Y-m-d", strtotime("+1 years", $package_date));
                            break;
                        case "monthly":
                            $total_detail_package = $package->pivot->length_of_term;
                            break;
                        case "daily":
                            $total_detail_package = $package->pivot->length_of_term;
                            break;
                        case "hourly":
                            $total_detail_package = 1;
                            break;
                        default:
                            $total_detail_package = 1;
                            $package_length_of_detail = $package->pivot->length_of_term;
                            break;
                    }
                    for ($j = 1; $j <= $total_detail_package; $j++) {
                        $package_counter_for_start_date = $j - 1;
                        switch ($package->pivot->price_type) {
                            case "yearly":
                                $package_start_date = date("Y-m-d", strtotime("+" . $package_counter_for_start_date . " years", $package_date));
                                $package_end_date = date("Y-m-d", strtotime("+" . $j . " years", $package_date));
                                $package_end_date = date("Y-m-d", strtotime("-1 day", strtotime($package_end_date)));
                                $package_start_time = $package->pivot->start_time;
                                $package_end_time = $package->pivot->end_time;
                                break;
                            case "monthly":
                                $package_start_date = date("Y-m-d", strtotime("+" . $package_counter_for_start_date . " month", $package_date));
                                $package_end_date = date("Y-m-d", strtotime("+" . $j . " month", $package_date));
                                $package_end_date = date("Y-m-d", strtotime("-1 day", strtotime($package_end_date)));
                                $package_start_time = $package->pivot->start_time;
                                $package_end_time = $package->pivot->end_time;
                                break;
                            case "daily":
                                $package_start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " day", $package_date));
                                $package_end_date = date("Y-m-d", strtotime("+" . $j . " day", $package_date));
                                if ($j == 1) {
                                    $package_start_time = $package->pivot->start_time;
                                }
                                if ($j > 1) {
                                    $package_start_time = '00:00:00';
                                }
                                if ($total_booking >= 1) {
                                    $package_end_time = '23:59:59';
                                }
                                if ($j == $total_booking) {
                                    $package_end_time = $package->pivot->end_time;
                                }
                                if ($booking->start_date_counted == "Y") {
                                    $package_end_date = $package_start_date;
                                }
                                break;
                            case "hourly":
                                $start_date = date('Y-m-d', strtotime($booking->start_date));
                                $end_date = date('Y-m-d', strtotime($booking->end_date));
                                $start_time = $booking->start_time;
                                $end_time = $booking->end_time;

                                if ($booking->length_of_term_after_office > 0) {
                                    $end_time = $office_hour_end . ":00:00";
                                }
                                break;
                        }

                        $booking_detail = new BookingDetail;

                        $detail_price = $package->pivot->detail_price;
                        if ($booking->usable_discount == "percentage") {
                            $detail_discount_price = $detail_price * ($booking->discount_percentage / 100);
                        } else if ($booking->usable_discount == "price") {
                            if ($total_discount_price > 0) {
                                $temp = $total_discount_price - $single_discount_price;
                                if ($temp < 0) {
                                    $detail_discount_price = $total_discount_price;
                                } else {
                                    $detail_discount_price = $single_discount_price;
                                }
                                $total_discount_price = $total_discount_price - $detail_discount_price;
                            }
                        }
                        $detail_price = $detail_price - $detail_discount_price;

                        if ($detail_price > 0) {
                            if ($package->has_service_charge == "Y") {
                                $detail_service_charge = round($detail_price * $service_charge);
                            } else {
                                $detail_service_charge = 0;
                            }
                            switch ($booking->tax_status) {
                                case "exclude":
                                    $detail_tax_price = ($detail_price + $detail_service_charge) * $tax_percentage;
                                    break;
                                case "include":
                                    $temp = $detail_price;
                                    $detail_price = round($temp / (1 + $tax_percentage));
                                    $detail_tax_price = $temp - $detail_price;
                                    $detail_service_charge = 0;
                                    $has_service_charge = $package->has_service_charge;

                                    if ($has_service_charge == "Y") {
                                        $temp_1 = $detail_price;
                                        $detail_price = round($temp_1 / (1 + $service_charge));
                                        $detail_service_charge = $temp_1 - $detail_price;
                                    }

                                    break;
                            }
                        }

                        $booking_detail->booking_id = $booking->id;
                        $booking_detail->package_id = $package->id;
                        $booking_detail->month = $package_month;
                        $booking_detail->year = $package_year;
                        $booking_detail->detail_sequence = $j;
                        $booking_detail->detail_price = $detail_price;
                        $booking_detail->detail_service_charge = $detail_service_charge;
                        $booking_detail->detail_tax_price = $detail_tax_price;
                        $booking_detail->usable_discount = $booking->usable_discount;
                        $booking_detail->detail_discount_percentage = $booking->discount_percentage;
                        $booking_detail->detail_discount_price = $detail_discount_price;
                        $booking_detail->quantity = $package->pivot->quantity;
                        $booking_detail->start_time = $package_start_time;
                        $booking_detail->end_time = $package_end_time;
                        $booking_detail->start_date = $package_start_date;
                        $booking_detail->end_date = $package_end_date;
                        $booking_detail->length_of_detail = $package_length_of_detail;

                        if (!$booking_detail->save()) {
                            $return = false;
                            break;
                        }

                        switch ($package->pivot->price_type) {
                            case "yearly":
                                $package_year++;
                                break;
                            case "monthly":
                                $package_month++;
                                if ($package_month > 12) {
                                    $package_month = $package_month - 12;
                                    $package_year++;
                                }
                                break;
                            case "daily":
                                $package_month = date("m", strtotime($package_start_date));
                                $package_year = date("Y", strtotime($package_start_date));
                                break;
                            default:
                                break;
                        }
                    }
                }
            } elseif ($booking->type == "product") {
                $detail_price = $booking->detail_price;
                if (($total_booking - $i + 1) > $booking->free_term_booking) {
                    if ($booking->usable_discount == "percentage") {
                        $detail_discount_price = $detail_price * ($booking->discount_percentage / 100);
                    } else if ($booking->usable_discount == "price") {
                        if ($total_discount_price > 0) {
                            $temp = $total_discount_price - $single_discount_price;
                            if ($temp < 0) {
                                $detail_discount_price = $total_discount_price;
                            } else {
                                $detail_discount_price = $single_discount_price;
                            }
                            $total_discount_price = $total_discount_price - $detail_discount_price;
                        }
                    }
                    $detail_price = $detail_price - $detail_discount_price;
                } else {
                    $detail_price = 0;
                }

                if ($detail_price > 0) {
                    if ($booking->product->has_service_charge == "Y") {
                        $detail_service_charge = $detail_price * $service_charge;
                    } else {
                        $detail_service_charge = 0;
                    }
                    switch ($booking->tax_status) {
                        case "exclude":
                            $detail_tax_price = ($detail_price + $detail_service_charge) * $tax_percentage;
                            break;
                        case "include":
                            $temp = $detail_price;
                            $detail_price = round($temp / (1 + $tax_percentage));
                            $detail_tax_price = $temp - $detail_price;
                            $detail_service_charge = 0;
                            $has_service_charge = $booking->product->has_service_charge;

                            if ($has_service_charge == "Y") {
                                $temp_1 = $detail_price;
                                $detail_service_charge = round($temp / (1 + $service_charge));
                                $detail_service_charge = $temp - $detail_price;
                            }

                            break;
                    }
                }

                $product_id = $booking->product_id;
                $package_id = $booking->package_id;
                $quantity = $booking->quantity;

                $booking_detail = new BookingDetail;
                $booking_detail->booking_id = $booking->id;
                $booking_detail->product_id = $product_id;
                $booking_detail->package_id = $package_id;
                $booking_detail->month = $month;
                $booking_detail->year = $year;
                $booking_detail->detail_sequence = $i;
                $booking_detail->detail_price = $detail_price;
                $booking_detail->detail_service_charge = $detail_service_charge;
                $booking_detail->detail_tax_price = $detail_tax_price;
                $booking_detail->usable_discount = $booking->usable_discount;
                $booking_detail->detail_discount_percentage = $booking->discount_percentage;
                $booking_detail->detail_discount_price = $detail_discount_price;
                $booking_detail->start_time = $start_time;
                $booking_detail->end_time = $end_time;
                $booking_detail->start_date = $start_date;
                $booking_detail->end_date = $end_date;
                $booking_detail->quantity = $quantity;
                $booking_detail->length_of_detail = $length_of_detail;

                if (!$booking_detail->save()) {
                    $return = false;
                    break;
                }
            }

            switch ($booking->price_type) {
                case "yearly":
                    $year++;
                    break;
                case "monthly":
                    $month++;
                    if ($month > 12) {
                        $month = $month - 12;
                        $year++;
                    }
                    break;
                case "daily":
                    $month = date("m", strtotime($start_date));
                    $year = date("Y", strtotime($start_date));
                    break;
                default:
                    break;
            }
        }

        if ($booking->length_of_term_after_office > 0) {
            $return = self::create_booking_detail_after_office_hour($booking);
        }

        if ($booking->booking_id != null && $booking->is_renewal == "Y") {
            $reference_booking = Booking::findOrFail($booking->booking_id);
            $reference_booking->status_id = 4;
            if (!$reference_booking->save()) {
                $return = false;
            }
        }

        if ($booking->inquiry_id != null) {
            $inquiry = $booking->inquiry;
            $inquiry->status_id = 4;
            $inquiry->complete_by = $booking->posting_by;
            if ($inquiry->save()) {
                foreach ($inquiry->proforma as $proforma) {
                    $proforma->status_id = 4;
                    $proforma->complete_by = $booking->posting_by;
                    if (!$proforma->save()) {
                        $return = false;
                        break;
                    }
                }
            } else {
                $return = false;
            }
        }

        $return = self::sendCreatorNotification($booking);

        return $return;
    }

    public static function create_booking_detail_after_office_hour($booking)
    {
        $return = true;

        $parameter_of_tax_percentage = ParameterSetting::where('name', 'tax_percentage')->first();
        $tax_percentage = $parameter_of_tax_percentage->double_value;
        $parameter_of_service_charge = ParameterSetting::where('name', 'service_charge')->first();
        $service_charge = $parameter_of_service_charge->double_value;
        $parameter_of_office_hour_end = ParameterSetting::where('name', 'office_hour_end')->first();
        $office_hour_end = $parameter_of_office_hour_end->int_value;

        $month = date("m", strtotime($booking->start_date));
        $year = date("Y", strtotime($booking->start_date));
        $date = strtotime($booking->start_date);

        $total_discount_price = $booking->discount_price;

        if ($total_discount_price > 0) {
            if ($booking->type == "room") {
                $single_discount_price = ceil($total_discount_price / sizeof($booking->rooms));
            } else if ($booking->type == "package") {
                $single_discount_price = ceil($total_discount_price / sizeof($booking->packages));
            } else {
                $single_discount_price = 0;
            }
        } else {
            $single_discount_price = 0;
        }

        if ($booking->type == "room") {
            switch ($booking->price_type) {
                case "yearly":
                    $start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " years", $date));
                    $end_date = date("Y-m-d", strtotime("+" . $i . " years", $date));
                    $end_date = date("Y-m-d", strtotime("-1 day", strtotime($end_date)));
                    $start_time = $booking->start_time;
                    $end_time = $booking->end_time;
                    break;
                case "monthly":
                    $start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " month", $date));
                    $end_date = date("Y-m-d", strtotime("+" . $i . " month", $date));
                    $end_date = date("Y-m-d", strtotime("-1 day", strtotime($end_date)));
                    $start_time = $booking->start_time;
                    $end_time = $booking->end_time;
                    break;
                case "daily":
                    $start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " day", $date));
                    $end_date = date("Y-m-d", strtotime("+" . $i . " day", $date));
                    if ($i == 1) {
                        $start_time = $booking->start_time;
                    }
                    if ($i > 1) {
                        $start_time = '00:00:00';
                    }
                    if ($total_booking >= 1) {
                        $end_time = '23:59:59';
                    }
                    if ($i == $total_booking) {
                        $end_time = $booking->end_time;
                    }
                    if ($booking->start_date_counted == "Y") {
                        $end_date = $start_date;
                    }
                    break;
                case "hourly":
                    $start_date = date('Y-m-d', strtotime($booking->start_date));
                    $end_date = date('Y-m-d', strtotime($booking->end_date));
                    $start_time = $office_hour_end . ":00:00";
                    $end_time = $booking->end_time;
                    break;
            }

            foreach ($booking->rooms as $room) {
            	$detail_price = $room->pivot->other_price;
                $detail_discount_price = 0;
                $detail_service_charge = 0;
                $detail_tax_price = 0;
                

                if ($booking->usable_discount == "percentage") {
                    $detail_discount_price = $detail_price * ($booking->discount_percentage / 100);
                } else if ($booking->usable_discount == "price") {
                    if ($total_discount_price > 0) {
                        $temp = $total_discount_price - $single_discount_price;
                        if ($temp < 0) {
                            $detail_discount_price = $total_discount_price;
                        } else {
                            $detail_discount_price = $single_discount_price;
                        }
                        $total_discount_price = $total_discount_price - $detail_discount_price;
                    }
                }

                $detail_price = $detail_price - $detail_discount_price;
                if ($detail_price > 0) {
                    if ($booking->service_charge_status == "Y") {
                        $detail_service_charge = round($detail_price * $service_charge);
                    } else {
                        $detail_service_charge = 0;
                    }
                    switch ($booking->tax_status) {
                        case "exclude":
                            $detail_tax_price = ($detail_price + $detail_service_charge) * $tax_percentage;
                            break;
                        case "include":
                            $temp = $detail_price;
                            $detail_price = round($temp / (1 + $tax_percentage));
                            $detail_tax_price = $temp - $detail_price;
                            $detail_service_charge = 0;
                            $has_service_charge = "N";

                            if ($has_service_charge == "Y") {
                                $temp_1 = $detail_price;
                                $detail_service_charge = round($temp / (1 + $service_charge));
                                $detail_service_charge = $temp - $detail_price;
                            }

                            $temp_1 = $detail_price;
                            $detail_service_charge = round($temp / (1 + $service_charge));
                            $detail_service_charge = $temp - $detail_price;
                            break;
                    }
                }

                $booking_detail = new BookingDetail;
                $booking_detail->booking_id = $booking->id;
                $booking_detail->room_id = $room->id;
                $booking_detail->month = $month;
                $booking_detail->year = $year;
                $booking_detail->detail_sequence = 1;
                $booking_detail->detail_price = $detail_price;
                $booking_detail->detail_service_charge = $detail_service_charge;
                $booking_detail->detail_tax_price = $detail_tax_price;
                $booking_detail->usable_discount = $booking->usable_discount;
                $booking_detail->detail_discount_percentage = $booking->discount_percentage;
                $booking_detail->detail_discount_price = $detail_discount_price;
                $booking_detail->start_time = $start_time;
                $booking_detail->end_time = $end_time;
                $booking_detail->start_date = $start_date;
                $booking_detail->end_date = $end_date;
                $booking_detail->length_of_detail = $booking->length_of_term_after_office;

                if (!$booking_detail->save()) {
                    $return = false;
                    break;
                }
            }
        }

        return $return;
    }

    public static function create_booking_order($booking)
    {
        $return = true;

        $parameter_of_tax_percentage = ParameterSetting::where('name', 'tax_percentage')->first();
        $tax_percentage = $parameter_of_tax_percentage->double_value;
        $parameter_of_service_charge = ParameterSetting::where('name', 'service_charge')->first();
        $service_charge = $parameter_of_service_charge->double_value;

        $check_exist_order = Order::where('booking_id', $booking->id)->where('include_into_main_agreement', 'Y')->first();
        if ($check_exist_order == null) {
            $order = new Order;
            $order->code = HomeController::getTransactionCode('orders', 'POS', $booking->location_id);
        } else {
            $order = $check_exist_order;
        }
        $order->status_id = $booking->status_id;
        $order->location_id = $booking->location_id;
        $order->customer_id = $booking->customer_id;
        $order->booking_id = $booking->id;
        $order->order_date = date('Y-m-d');
        $order->total_price = $booking->total_additional_charge;
        $order->total_service_charge = $booking->total_service_charge_additional_charge;
        $order->total_tax_price = $booking->total_tax_additional_charge;
        $order->tax_status = $booking->tax_status;
        $order->include_into_main_agreement = 'Y';
        if ($order->save()) {
            if ($check_exist_order != null) {
                OrderDetail::where('order_id', $order->id)->delete();
            }
            foreach ($booking->products as $product) {
                $ac_detail_price = $product->pivot->detail_price;
                $ac_detail_service_charge = 0;
                $ac_detail_tax_price = 0;
                if ($product->has_service_charge == "Y") {
                    $ac_detail_service_charge = $ac_detail_price * $service_charge;
                }
                switch ($booking->tax_status) {
                    case "exclude":
                        $ac_detail_tax_price = ($ac_detail_price + $ac_detail_service_charge) * $tax_percentage;
                        break;
                    case "include":
                        $temp = $ac_detail_price;
                        if ($product->has_service_charge == "Y") {
                            $temp_1 = round($temp / (1 + $tax_percentage));
                            $ac_detail_tax_price = $temp - $temp_1;
                            $ac_detail_price = round($temp_1 / (1 + $service_charge));
                            $ac_detail_service_charge = $temp_1 - $ac_detail_price;
                        } else {
                            $ac_detail_price = round($temp / (1 + $tax_percentage));
                            $ac_detail_tax_price = $temp - $ac_detail_price;
                        }
                        break;
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->product_id = $product->id;
                $order_detail->quantity = $product->pivot->quantity;
                $order_detail->start_date = $product->pivot->start_date;
                $order_detail->end_date = $product->pivot->end_date;
                $order_detail->start_time = $product->pivot->start_time;
                $order_detail->end_time = $product->pivot->end_time;
                $order_detail->length_of_term = $product->pivot->length_of_term;
                $order_detail->detail_price = $ac_detail_price;
                $order_detail->detail_service_charge = $ac_detail_service_charge;
                $order_detail->detail_tax_price = $ac_detail_tax_price;
                if (!$order_detail->save()) {
                    $return = false;
                    break;
                }
            }
        } else {
            $return = false;
        }

        return $return;
    }

    public static function create_booking_deposit($booking)
    {
        $return = true;

        $return = true;
        $is_new_deposit = false;

        if ($booking->security_deposit > 0) {
            if ($booking->is_renewal == "Y" && $booking->booking_id != null) {
                $reference_booking = Booking::findOrFail($booking->booking_id);
                $deposit = $reference_booking->deposit;
                if ($deposit != null) {
                    $is_new_deposit = true;
                }
            }

            if ($is_new_deposit) {
                if ($deposit->total_paid > 0) {
                    $deposit->payment_status = 'HP';
                } else {
                    $deposit->payment_status = 'NP';
                }
            } else {
                $deposit = new Deposit;
                $deposit->status_id = $booking->status_id;
                $deposit->location_id = $booking->location_id;
                $deposit->customer_id = $booking->customer_id;
                $deposit->code = HomeController::getTransactionCode('deposits', 'DEP', $booking->location_id);
                $deposit->type_security_deposit = 'IN';
                $deposit->category = 'security_deposit';
                $deposit->payment_status = 'NP';
            }

            $deposit->total_deposit = $booking->security_deposit;
            $deposit->due_date = date("Y-m-d");
            if (!$deposit->save()) {
                $return = false;
            } else {
                $booking->deposit_id = $deposit->id;
                $booking->save();
            }
        }

        // if ($booking->security_deposit > 0) {
        //     $deposit = new Deposit;
        //     $deposit->status_id = $booking->status_id;
        //     $deposit->location_id = $booking->location_id;
        //     $deposit->customer_id = $booking->customer_id;
        //     $deposit->code = HomeController::getTransactionCode('deposits', 'DEP', $booking->location_id);
        //     $deposit->type_security_deposit = 'IN';
        //     $deposit->category = 'security_deposit';
        //     $deposit->customer_id = $booking->customer_id;
        //     $deposit->payment_status = 'NP';
        //     $deposit->total_deposit = $booking->security_deposit;

        //     if (!$deposit->save()) {
        //         $return = false;
        //     }
        // }

        return $return;
    }

    public static function booking_complimentary($booking)
    {
        $return = true;

        $month = date("m", strtotime($booking->start_date));
        $year = date("Y", strtotime($booking->start_date));
        $date = strtotime($booking->start_date);

        switch ($booking->price_type) {
            case "yearly":
                $total_complimentary = $booking->length_of_term * 12;
                $end_date = date("Y-m-d", strtotime("+1 years", $date));
                break;
            case "monthly":
                $total_complimentary = $booking->length_of_term;
                $end_date = date("Y-m-d", strtotime("+1 month", $date));
                break;
            case "daily":
                $total_complimentary = HomeController::dateDifference($booking->start_date, $booking->end_date, '%m');
                if ($total_complimentary == 0) {
                    $total_complimentary = 1;
                }
                break;
            default:
                $total_complimentary = 1;
                break;
        }

        for ($i = 1; $i <= $total_complimentary; $i++) {
            foreach ($booking->complimentarys as $complimentary) {
                $counter_for_start_date = $i - 1;
                $start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " month", $date));
                $end_date = date("Y-m-d", strtotime("+" . $i . " month", $date));

                $booking_complimentary = new BookingComplimentary;
                $booking_complimentary->booking_id = $booking->id;
                $booking_complimentary->complimentary_id = $complimentary->id;
                $booking_complimentary->total_complimentary = $complimentary->pivot->total_complimentary;
                $booking_complimentary->month = $month;
                $booking_complimentary->year = $year;
                $booking_complimentary->month_sequence = $i;
                $booking_complimentary->start_date = $start_date;
                $booking_complimentary->end_date = $end_date;

                if (!$booking_complimentary->save()) {
                    $return = false;
                    break;
                }
            }

            $month++;
            if ($month > 12) {
                $month = $month - 12;
                $year++;
            }
        }

        return $return;
    }

    public static function create_technical_meeting($booking)
    {
        $return = true;

        $create_technical_meeting = false;
        $create_technical_meeting_product_id = null;
        if ($booking->product_id != null) {
            if (sizeof($booking->product->product_areas) > 0) {
                $create_technical_meeting = true;
                $create_technical_meeting_product_id = $booking->product_id;
            }
        }
        if ($booking->package_id != null) {
            foreach ($booking->package->product as $product) {
                if (sizeof($product->product_area) > 0) {
                    $create_technical_meeting = true;
                    $create_technical_meeting_product_id = $product->id;
                    break;
                }
            }
        }

        if ($create_technical_meeting) {
            $product = Product::findOrFail($create_technical_meeting_product_id);
            $technical_meeting = new TechnicalMeeting;
            $technical_meeting->status_id = 1;
            $technical_meeting->location_id = $booking->location_id;
            $technical_meeting->booking_id = $booking->id;
            if ($technical_meeting->save()) {
                foreach ($product->product_area as $product_area) {
                    $technical_meeting_area = new TechnicalMeetingArea;
                    $technical_meeting_area->technical_meeting_id = $technical_meeting->id;
                    $technical_meeting_area->area_id = $product_area->area_id;
                    if ($technical_meeting_area->save()) {
                        foreach ($product_area->product_area_detail as $product_area_detail) {
                            $technical_meeting_area_detail = new TechnicalMeetingAreaDetail;
                            $technical_meeting_area_detail->technical_meeting_area_id = $technical_meeting_area->id;
                            $technical_meeting_area_detail->name = $product_area_detail->name;
                            $technical_meeting_area_detail->desc = $product_area_detail->desc;
                            if (!$technical_meeting_area_detail->save()) {
                                $return = false;
                                break;
                            }
                        }
                    } else {
                        $return = false;
                        break;
                    }
                }
            } else {
                $return = false;
            }
        }

        return $return;
    }

    public function complimentary(Request $request, $customer_id)
    {
        $active_status_id = array(1, 2, 4);
        $year = intval(date('Y', strtotime($request['start_date'])));
        $month = intval(date('m', strtotime($request['start_date'])));
        $room_category_id = $request['room_category_id'];
        $price_type = $request['price_type'];
        $booking_id = $request['booking_id'];
        $data['complimentary'] = '';
        $total_complimentary = 0;
        $total_use_complimentary = 0;

        $complimentary = Complimentary::where('room_category_id', $room_category_id)
            ->where('price_type', $price_type)
            ->first();

        if ($complimentary != null) {
            $total_complimentary = BookingComplimentary::join('bookings', 'bookings.id', 'booking_complimentaries.booking_id')
                ->join('complimentarys', 'booking_complimentaries.complimentary_id', 'complimentarys.id')
                ->where('booking_complimentaries.complimentary_id', $complimentary->id)
                ->where('bookings.customer_id', $customer_id)
                ->whereIn('bookings.status_id', $active_status_id)
                ->where('month', $month)
                ->where('year', $year)
                ->sum('booking_complimentaries.total_complimentary');

            if ($booking_id != null) {
                $total_use_complimentary = BookingDetail::join('complimentarys', 'complimentarys.id', 'booking_details.complimentary_id')
                    ->join('bookings', 'bookings.id', 'booking_details.booking_id')
                    ->where('bookings.customer_id', $customer_id)
                    ->where('booking_details.booking_id', '!=', $booking_id)
                    ->where('booking_details.complimentary_id', $complimentary->id)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->sum('booking_details.detail_use_complimentary');
            } else {
                $total_use_complimentary = BookingDetail::join('complimentarys', 'complimentarys.id', 'booking_details.complimentary_id')
                    ->join('bookings', 'bookings.id', 'booking_details.booking_id')
                    ->where('bookings.customer_id', $customer_id)
                    ->where('booking_details.complimentary_id', $complimentary->id)
                    ->whereIn('bookings.status_id', $active_status_id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->sum('booking_details.detail_use_complimentary');
            }
        }


        $data['total_available_complimentary'] = $total_complimentary - $total_use_complimentary;
        $data['complimentary'] = $complimentary;

        return $data;
    }

    public function get_schedule_room(Request $request)
    {
        $list = array();
        $days = array();
        $month = $request['month'];
        $location = $request['location'];
        $first_day_of_month = date($request['year'] . '-' . $month . '-01');
        $last_day_of_month = date('t', strtotime($first_day_of_month));
        $month_name = date('F', strtotime($first_day_of_month));
        $room_category = $request['room_category'];

        $rooms = Room::select('rooms.*')
            ->leftjoin('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->leftjoin('room_categories', 'r_c_and_room.room_category_id', 'room_categories.id')
            ->where('room_categories.code', $room_category)
            ->where('location_id', $location)
            ->get();

        for ($d = 1; $d <= intval($last_day_of_month); $d++) {
            $selected_date = $d;

            if ($selected_date < 10) {
                $selected_date = '0' . $selected_date;
            }

            array_push($days, Carbon::parse($request['year'] . '-' . $month . '-' . $d)->format('D, d'));

            foreach ($rooms as $no => $detail) {
                $booking_detail = BookingDetail::select('booking_details.*', 'customers.name as customer_name')
                    ->join('bookings', 'booking_details.booking_id', 'bookings.id')
                    ->join('customers', 'bookings.customer_id', 'customers.id')
                    ->join('rooms', 'booking_details.room_id', 'rooms.id')
                    ->where('rooms.id', $detail->id)
                    ->where('booking_details.start_date', '<=', date('Y-' . $month . '-' . $selected_date))
                    ->where('booking_details.end_date', '>', date('Y-' . $month . '-' . $selected_date))
                    ->first();

                $list[$d - 1][$no] = $booking_detail;
            }
        }

        $data['list'] = $list;
        $data['days'] = $days;
        $data['rooms'] = $rooms;
        $data['month_name'] = $month_name;

        return $data;
    }

    public function getCustomerContact($customer_id, $contact_id)
    {
        $return['customer'] = Customer::findOrFail($customer_id);
        $return['contact'] = Contact::findOrFail($contact_id);
        $return['contact']->birth_date = date('m/d/Y', strtotime($return['contact']->birth_date));

        return $return;
    }

    public function updateCustomerContact(Request $request, $customer_id, $contact_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $contact = Contact::findOrFail($contact_id);
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url)
                ->withErrors($validator)
                ->withInput();
        } else {
            $customer->nature_of_business_id = $request['nature_of_business_id'];
            $customer->nature_of_business = $request['nature_of_business'];
            $customer->customer_type = $request['customer_type'];
            $customer->name = $request['customer_name'];
            $customer->email = $request['customer_email'];
            $customer->phone = $request['customer_phone'];
            $customer->mobile_phone = $request['customer_mobile_phone'];
            $customer->fax = $request['customer_fax'];
            $customer->address = $request['customer_address'];
            $customer->country = $request['customer_country'];
            $customer->city = $request['customer_city'];
            $customer->zipcode = $request['customer_zipcode'];
            $customer->tax_number = $request['customer_tax_number'];
            $customer->updated_by = Auth::user()->name;
            if ($customer->save()) {
                $contact->honorific = $request['contact_honorific'];
                $contact->name = $request['contact_name'];
                $contact->email = $request['contact_email'];
                $contact->id_number = $request['contact_id_number'];
                $contact->phone = $request['contact_phone'];
                $contact->mobile_phone = $request['contact_mobile_phone'];
                if (!empty($request['contact_birth_date'])) {
                    $contact->birth_date = date('Y-m-d', strtotime($request['contact_birth_date']));
                }
                if ($contact->save()) {
                    \Session::flash('success', 'You are success in updating your data');
                } else {
                    \Session::flash('error', 'You are failed in updating your data !!!');
                }
            } else {
                \Session::flash('error', 'You are failed in updating your data !!!');
            }
            return Redirect::to($request['back_url']);
        }
    }

    public function get_by_customer_id(Request $request, $id)
    {
        return Booking::where('customer_id', $id)->get();
    }

    public static function createCustomCode($table_name, $prefix_name, $location_id, $is_renewal = "N")
    {
        // $code = HomeController::getTransactionCode($table_name, $prefix_name, $location_id);

        // if ($is_renewal == "Y") {
        //     $code = "RN-" . $code;
        // } else {
        //     $code = "N-" . $code;
        // }

        // return $code;
        $check_unique_code = false;

        $location = Location::findOrFail($location_id);
        $total_data = DB::table($table_name)
            ->where('location_id', $location_id)
            ->where('created_at', '>=', date('Y-01-01 00:00:00'))
            ->where('created_at', '<=', date('Y-12-31 23:59:59'))
            ->count();
        $sequence = $total_data + 1;
        while (!$check_unique_code) {
            $code = $prefix_name . '/' . $location->code . '/' . date("Y") . '/' . HomeController::setRomawi((int) (date("m"))) . '/' . HomeController::setZero($sequence);
            // $code = self::getTransactionCode($table_name, $prefix_name, $location_id);

            if ($is_renewal == "Y") {
                $code = "RN-" . $code;
            } else {
                $code = "N-" . $code;
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

    public function migration()
    {
        // Uncoment this code when migration and doing it at local enviornment
        // $return = "true";
        // $bookings = Booking::get();
        // foreach($bookings as $no => $booking){
        //     BookingDetail::where('booking_id', $booking->id)->delete();
        //     if(self::create_booking_detail($booking)){
        //         // Continue
        //     }else{
        //         $return = "false";
        //         break;
        //     }

        //     BookingComplimentary::where('booking_id', $booking->id)->delete();
        //     if(self::booking_complimentary($booking)){
        //         // Continue
        //     }else{
        //         $return = "false";
        //         break;
        //     }
        // }

        // echo $return;
    }

    public function getDedicated(Request $request)
    {
        $booking_id = $request['booking_id'];
        $return['bookings'] = Booking::select('booking_dedicated_phones.dedicated_phone_id as id', 'dedicated_phones.number as number')
            ->join('booking_dedicated_phones', 'booking_dedicated_phones.booking_id', 'bookings.id')
            ->join('dedicated_phones', 'dedicated_phones.id', 'booking_dedicated_phones.dedicated_phone_id')
            ->where('booking_dedicated_phones.booking_id', $booking_id)
            ->get();

        return $return;
    }

    // public static function sendNotificationDedicated($booking)
    // {
    //     $return = true;

    //     $array_access_group_id = array();
    //     $url = 'dedicated_phone_transaction';



    //     /* Versi Lama
    //     self::get_parent_of_this_employee($booking->employee_id);
    //     $employees = Employee::whereIn('id', self::$parent_ids)->get();
    //     */

    //     $a_g_and_module = DB::table('a_g_and_module')
    //         ->join('modules', 'modules.id', 'a_g_and_module.module_id')
    //         ->where('a_g_and_module.isExec', 1)
    //         ->where('modules.link', $url)
    //         ->get();

    //     foreach ($a_g_and_module as $detail) {
    //         array_push($array_access_group_id, $detail->access_group_id);
    //     }

    //     $employees = Employee::join('users', 'users.id', 'employees.user_id')
    //         ->whereIn('users.access_group_id', $array_access_group_id)
    //         ->get();

    //     foreach ($employees as $no => $detail) {
    //         $check_exist_notification = Notification::where('url', $url . '/' . $booking->id . '/edit')
    //             ->where('user_id', $detail->user->id)
    //             ->where('read_status', 'N')
    //             ->first();

    //         if ($check_exist_notification == null) {
    //             $notification = new Notification;
    //             $notification->user_id = $detail->user->id;
    //             $notification->header = "Approve Booking No = " . $booking->code;
    //             $notification->body = "Booking no = " . $booking->code . " has been created by " . $booking->employee->name . " at" . $booking->created_at;
    //             $notification->url = $url . '/' . $booking->id . '/edit';

    //             if (!$notification->save()) {
    //                 $return = false;
    //                 break;
    //             }
    //         }
    //     }

    //     return $return;
    // }
}
