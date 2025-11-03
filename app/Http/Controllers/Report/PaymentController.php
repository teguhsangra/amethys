<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Payment;
use App\Models\Employee;
use App\Exports\PaymentExport;
use Carbon\Carbon;
use DataTables;
use Validator;
use DateTime;
use Redirect;
use Excel;
use Auth;
use DB;

class PaymentController extends Controller
{
    private $url = 'payment_report';
    private $form_id = 'payment_report_form';
    private $table_name = 'payments';
    private $prefix_name = 'Payment';
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

        $active_status_id = array(1, 2, 4);

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

        $date_interval = $first_of_start_date->diff($end_of_end_date);
        if ($date_interval->y > 0) {
            $total_month = $date_interval->m + ($date_interval->y * 12);
        } else {
            $total_month = $date_interval->m;
        }

        $array_of_first_month = array();
        $array_of_str_month = array();
        $array_of_str_year = array();
        $array_of_end_month = array();
        $array_of_payment = array();

        for ($i = 0; $i <= $total_month; $i++) {
            if ($i == 0) {
                $array_of_first_month[$i] = $first_of_start_date->format('Y-m-d');
                $array_of_str_month[$i] = $first_of_start_date->format('M');
                $array_of_str_year[$i] = $first_of_start_date->format('Y');
                $temp_fem = new DateTime(date("Y-m-t", strtotime($first_of_start_date->format('Y-m-d'))));
                $array_of_end_month[$i] = $temp_fem->format('Y-m-d');
                $array_of_payment[$i] = Payment::where('payment_date', '>=', $array_of_first_month[$i])
                    ->where('payment_date', '<=', $array_of_end_month[$i])
                    ->where('location_id', $locations_id)
                    ->whereIn('status_id', $active_status_id)
                    ->get();
            } else {
                $temp_fom = new DateTime(date('Y-m-d', strtotime("+" . $i . " months", strtotime($first_of_start_date->format('Y-m-d')))));
                $array_of_first_month[$i] = $temp_fom->format('Y-m-d');
                $array_of_str_month[$i] = $temp_fom->format('M');
                $array_of_str_year[$i] = $temp_fom->format('Y');
                $temp_fem_am = new DateTime(date('Y-m-t', strtotime("+" . $i . " months", strtotime($first_of_start_date->format('Y-m-d')))));
                $array_of_end_month[$i] = $temp_fem_am->format('Y-m-d');
                $array_of_payment[$i] = Payment::where('payment_date', '>=', $array_of_first_month[$i])
                    ->where('payment_date', '<=', $array_of_end_month[$i])
                    ->where('location_id', $locations_id)
                    ->whereIn('status_id', $active_status_id)
                    ->get();
            }
        }
        $data['total_month'] = $total_month;
        $data['first_of_start_date'] = $first_of_start_date->format('Y-m-d');
        $data['end_of_end_date'] = $end_of_end_date->format('Y-m-d');

        $data['array_of_first_month'] = $array_of_first_month;
        $data['array_of_end_month'] = $array_of_end_month;
        $data['array_of_str_month'] = $array_of_str_month;
        $data['array_of_str_year'] = $array_of_str_year;
        $data['array_of_payment'] = $array_of_payment;

        $data['payments'] = Payment::where('payment_date', '>=', $string_of_first_date)
            ->where('payment_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->where('location_id', $locations_id)
            ->whereIn('status_id', $active_status_id)
            ->get();

        return view('pages.report.payment.index', $data);
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
        $active_status_id = array(1, 2, 4);

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

        $array_of_first_month = array();
        $array_of_str_month = array();
        $array_of_str_year = array();
        $array_of_end_month = array();
        $array_of_payment = array();

        for ($i = 0; $i <= $total_month; $i++) {
            if ($i == 0) {
                $array_of_first_month[$i] = $first_of_start_date->format('Y-m-d');
                $array_of_str_month[$i] = $first_of_start_date->format('M');
                $array_of_str_year[$i] = $first_of_start_date->format('Y');
                $temp_fem = new DateTime(date("Y-m-t", strtotime($first_of_start_date->format('Y-m-d'))));
                $array_of_end_month[$i] = $temp_fem->format('Y-m-d');
                $array_of_payment[$i] = Payment::where('payment_date', '>=', $array_of_first_month[$i])
                    ->where('payment_date', '<=', $array_of_end_month[$i])
                    ->where('location_id', $locations_id)
                    ->whereIn('status_id', $active_status_id)
                    ->get();
            } else {
                $temp_fom = new DateTime(date('Y-m-d', strtotime("+" . $i . " months", strtotime($first_of_start_date->format('Y-m-d')))));
                $array_of_first_month[$i] = $temp_fom->format('Y-m-d');
                $array_of_str_month[$i] = $temp_fom->format('M');
                $array_of_str_year[$i] = $temp_fom->format('Y');
                $temp_fem_am = new DateTime(date('Y-m-t', strtotime("+" . $i . " months", strtotime($first_of_start_date->format('Y-m-d')))));
                $array_of_end_month[$i] = $temp_fem_am->format('Y-m-d');
                $array_of_payment[$i] = Payment::where('payment_date', '>=', $array_of_first_month[$i])
                    ->where('payment_date', '<=', $array_of_end_month[$i])
                    ->where('location_id', $locations_id)
                    ->whereIn('status_id', $active_status_id)
                    ->get();
            }
        }
        $data['total_month'] = $total_month;
        $data['first_of_start_date'] = $first_of_start_date->format('Y-m-d');
        $data['end_of_end_date'] = $end_of_end_date->format('Y-m-d');

        $data['array_of_first_month'] = $array_of_first_month;
        $data['array_of_end_month'] = $array_of_end_month;
        $data['array_of_str_month'] = $array_of_str_month;
        $data['array_of_str_year'] = $array_of_str_year;
        $data['array_of_payment'] = $array_of_payment;

        $data['payments'] = Payment::where('payment_date', '>=', $string_of_first_date)
            ->where('payment_date', '<=', $end_of_end_date->format('Y-m-d'))
            ->where('location_id', $locations_id)
            ->whereIn('status_id', $active_status_id)
            ->get();

        return Excel::download(new PaymentExport($data), 'Reports_Payment_' . date("YmdHis") . '.xlsx');
    }
}
