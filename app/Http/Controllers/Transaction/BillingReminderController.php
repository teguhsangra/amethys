<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\Customer;
use Yajra\DataTables\CollectionDataTable;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class BillingReminderController extends Controller
{
    private $url = 'billing_reminder';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['employee'] = $employee;

        return view('pages.transaction.billing_reminder.index', $data);
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

    public function datatables(Request $request)
    {
        $billing_array = array();

        $room_category_codes = array('SO');
        $active_status_ids = array(1, 2, 4);
        $booking_ids = array();
        $order_ids = array();
        $booking_detail_ids = array();
        $order_detail_ids = array();
        $billing_reminders = null;

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

        // $order_details = DB::table('order_details')
        //     ->join('orders', 'orders.id', 'order_details.order_id')
        //     ->join('customers', 'customers.id', 'orders.customer_id')
        //     ->select(
        //         'order_details.id',
        //         'order_details.payment_status',
        //         'orders.location_id',
        //         'orders.customer_id',
        //         'customers.name as customer_name',
        //         'order_details.start_date',
        //         'order_details.end_date',
        //         DB::raw('"POINT OF SALES" as category'),
        //         DB::raw('"order_detail" as type'),
        //     )
        //     ->where('order_details.payment_status', 'NP')
        //     ->where('orders.order_date', '<=', date('Y-m-t'))
        //     ->whereIn('orders.status_id', $active_status_ids)
        //     ->whereNotIn('order_details.id', $order_detail_ids)
        //     ->get();

        // $billing_reminders = $booking_details->merge($order_details);
        
        return DataTables::of($booking_details)->make(true);
    }
}
