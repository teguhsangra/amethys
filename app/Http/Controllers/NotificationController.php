<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ParameterSetting;
use App\Models\Notification;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\Task;
use App\User;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class NotificationController extends Controller
{
    private $url = 'notification';
    private $form_id = 'notification_form';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['url'] = $this->url;
        return view('pages.notification.index', $data);
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
        $notification = Notification::findOrFail($id);
        $notification->read_status = 'Y';
        $notification->save();

        $data['url'] = $this->url;
        $data['notification'] = $notification;
        return view('pages.notification.detail', $data);
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
        $notification = Notification::findOrFail($id);
        $notification->read_status = 'Y';
        $notification->save();

        return "true";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->get();

        return DataTables::of($notifications)->make(true);
    }

    public static function reminderNotification($user_id)
    {
        $parameter_of_reminder_default_month = ParameterSetting::where('name', 'reminder_default_month')->first();
        $reminder_default_month = $parameter_of_reminder_default_month->int_value;
        $reminder_end_date = date('Y-m-d', strtotime("+" . $reminder_default_month . " months"));
        $employee = Employee::where('user_id', $user_id)->first();

        $collection_reminder_a_g_and_module = HomeController::getAccess('collection_reminder', $user_id);

        if ($collection_reminder_a_g_and_module != null && $employee != null) {
            $array_of_status_ids = array(2, 4);

            $invoices = Invoice::where('payment_status', 'NP')
                ->where('due_date', '>=', date('Y-m-d'))
                ->whereIn('status_id', $array_of_status_ids)
                ->get();

            foreach ($invoices as $no => $detail) {
                $check_exist_notification = Notification::where('url', 'collection_reminder')
                    ->where('user_id', $user_id)
                    ->where('read_status', 'N')
                    ->first();
                if ($check_exist_notification == null) {
                    $notification = new Notification;
                    $notification->user_id = $user_id;
                    $notification->header = "Unpaid Invoice No = " . $detail->code;
                    $notification->body = "Invoice no = " . $detail->code . " must be follow up";
                    $notification->url = 'collection_reminder';
                    $notification->save();
                }
            }
        }

        $billing_reminder_a_g_and_module = HomeController::getAccess('billing_reminder', $user_id);

        if ($billing_reminder_a_g_and_module != null && $employee != null) {
            $room_category_codes = array('SO');
            $active_status_ids = array(1, 2, 4);
            $booking_ids = array();
            $order_ids = array();
            $booking_detail_ids = array();
            $order_detail_ids = array();
            $billing_reminders = null;
            $billing_array = array();

            $proformas = Proforma::whereIn('status_id', $active_status_ids)->get();
            foreach ($proformas as $proforma) {
                if ($proforma->booking_id != null) {
                    array_push($booking_ids, $proforma->booking_id);
                }

                if ($proforma->order_id != null) {
                    array_push($order_ids, $proforma->order_id);
                }

                foreach ($proforma->proforma_detail as $proforma_detail) {
                    if ($proforma_detail->booking_detail_id != null) {
                        array_push($booking_detail_ids, $proforma_detail->booking_detail_id);
                    }
                    if ($proforma_detail->order_detail_id != null) {
                        array_push($order_detail_ids, $proforma_detail->order_detail_id);
                    }
                }
            }

            $invoices = Invoice::whereIn('status_id', $active_status_ids)->get();
            foreach ($invoices as $invoice) {
                if ($invoice->booking_id != null) {
                    array_push($booking_ids, $invoice->booking_id);
                }

                if ($invoice->order_id != null) {
                    array_push($order_ids, $invoice->order_id);
                }

                foreach ($invoice->invoice_detail as $invoice_detail) {
                    if ($invoice_detail->booking_detail_id != null) {
                        array_push($booking_detail_ids, $invoice_detail->booking_detail_id);
                    }
                    if ($invoice_detail->order_detail_id != null) {
                        array_push($order_detail_ids, $invoice_detail->order_detail_id);
                    }
                }
            }

            $booking_details = DB::table('booking_details')
                ->join('bookings', 'bookings.id', 'booking_details.booking_id')
                ->join('customers', 'customers.id', 'bookings.customer_id')
                ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->select(
                    'booking_details.id',
                    'booking_details.payment_status',
                    'bookings.location_id',
                    'bookings.customer_id',
                    'customers.name as customer_name',
                    'booking_details.start_date',
                    'booking_details.end_date',
                    DB::raw('(CASE
                        WHEN bookings.type = "product" THEN "Virtual Office"
                        WHEN bookings.type = "package" THEN "Package"
                        WHEN bookings.type = "room" THEN
                            (CASE
                            WHEN room_categories.code = "SO" THEN "Serviced Office"
                            WHEN room_categories.code = "MR" THEN "Meeting Room"
                            WHEN room_categories.code = "CW" THEN "Workstation"
                            ELSE "Other Room" END)
                        ELSE "Nothing" END) AS category'),
                    DB::raw('"booking_detail" as type'),
                )
                ->where('booking_details.payment_status', 'NP')
                ->where('booking_details.start_date', '<=', date('Y-m-t'))
                ->whereIn('bookings.status_id', $active_status_ids)
                ->whereNotIn('booking_details.id', $booking_detail_ids)
                ->whereIn('room_categories.code', $room_category_codes)
                ->get();

            if (sizeof($booking_details) > 0) {
                $check_exist_notification = Notification::where('url', 'billing_reminder')
                    ->where('user_id', $user_id)
                    ->where('read_status', 'N')
                    ->first();
                if ($check_exist_notification == null) {
                    $notification = new Notification;
                    $notification->user_id = $user_id;
                    $notification->header = "Billing Reminder Follow Up ";
                    $notification->body = "There are " . sizeof($billing_array) . " Billing(s) need to follow up";
                    $notification->url = 'billing_reminder';
                    $notification->save();
                }
            }
        }
    }


    public static function notification($type, $id)
    {
        $employees = Employee::get();

        switch ($type) {
            case "create_task":
                $task = Task::findOrFail($id);
                foreach ($employees as $no => $detail) {
                    $check_exist_notification = Notification::where('url', 'task')
                        ->where('user_id', $detail->user_id)
                        ->where('read_status', 'N')
                        ->first();

                    if ($check_exist_notification == null) {
                        $notification = new Notification;
                        $notification->user_id = $detail->user_id;
                        $notification->header = "Task = " . $task->code . "-" . $detail->employee->name . '';
                        $notification->body = "Task has been created by " . $detail->user->name;
                        $notification->url = 'task/' . $detail->id;
                        $notification->save();
                    }
                }
                break;

            case "escalated_task":
                $task = Task::findOrFail($id);
                foreach ($employees as $no => $detail) {
                    $check_exist_notification = Notification::where('url', 'task')
                        ->where('user_id', $detail->user_id)
                        ->where('read_status', 'N')
                        ->first();

                    if ($check_exist_notification == null) {
                        $notification = new Notification;
                        $notification->user_id = $detail->user_id;
                        $notification->header = "Task = " . $task->code . "-" . $detail->employee->name . '';
                        $notification->body = "Task has been Estalated by " . $detail->user->name;
                        $notification->url = 'task/' . $detail->id;
                        $notification->save();
                    }
                }
                break;
            case "closed_task":
                $task = Task::findOrFail($id);
                foreach ($employees as $no => $detail) {
                    $check_exist_notification = Notification::where('url', 'task')
                        ->where('user_id', $detail->user_id)
                        ->where('read_status', 'N')
                        ->first();

                    if ($check_exist_notification == null) {
                        $notification = new Notification;
                        $notification->user_id = $detail->user_id;
                        $notification->header = "Task = " . $task->code . "-" . $detail->employee->name . '';
                        $notification->body = "Task has been Closed by " . $detail->user->name;
                        $notification->url = 'task/' . $detail->id;
                        $notification->save();
                    }
                }
                break;
        }
    }
}
