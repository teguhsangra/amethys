<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Invoice;
use App\Models\Employee;
use App\Exports\InvoiceExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Carbon\Carbon;
use DateTime;


class InvoiceController extends Controller
{

    private $url = 'invoice_report';
    private $form_id = 'invoice_report_form';
    private $table_name = 'invoices';
    private $prefix_name = 'INV';
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
        $locations_id = $request->input('location_id');




        $data['location_id'] = $locations_id;
        $data['start_date'] = $request->input('start_date');
        $data['end_date'] = $request->input('end_date');
        if ($data['start_date'] == null && $data['end_date'] == null) {
            $data['start_date'] = Carbon::now()->format("Y-m-d");
            $data['end_date'] = Carbon::now()->format("Y-m-d");
        }
        $data['paid_total_price'] = Invoice::join('customers', 'invoices.customer_id', 'customers.id')
            ->where('invoices.created_at', '>=', $data['start_date'])
            ->where('invoices.created_at', '<=', $data['end_date'])
            ->where('invoices.location_id', $data['location_id'])
            ->where('invoices.payment_status', 'PA')
            ->sum('total_price');

        $data['paid_tax_price'] = Invoice::join('customers', 'invoices.customer_id', 'customers.id')
            ->where('invoices.created_at', '>=', $data['start_date'])
            ->where('invoices.created_at', '<=', $data['end_date'])
            ->where('invoices.location_id', $data['location_id'])
            ->where('invoices.payment_status', 'PA')
            ->sum('total_tax_price');

        $data['not_paid_total_price'] = Invoice::join('customers', 'invoices.customer_id', 'customers.id')
            ->where('invoices.created_at', '>=', $data['start_date'])
            ->where('invoices.created_at', '<=', $data['end_date'])
            ->where('invoices.location_id', $data['location_id'])
            ->where('invoices.payment_status', 'NP')
            ->sum('total_price');

        $data['not_paid_tax_price'] = Invoice::join('customers', 'invoices.customer_id', 'customers.id')
            ->where('invoices.created_at', '>=', $data['start_date'])
            ->where('invoices.created_at', '<=', $data['end_date'])
            ->where('invoices.location_id', $data['location_id'])
            ->where('invoices.payment_status', 'NP')
            ->sum('total_tax_price');

        $data['total_invoice'] = Invoice::join('customers', 'invoices.customer_id', 'customers.id')
            ->where('invoices.created_at', '>=', $data['start_date'])
            ->where('invoices.created_at', '<=', $data['end_date'])
            ->where('invoices.location_id', $data['location_id'])
            ->count();

        return view('pages.report.invoice.index', $data);
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
        $active_status_id = array(2, 4);
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $location_id = $request->input('location_id');

        if (!empty($location_id)) {
            $invoices = Invoice::select(
                'invoices.*',
                DB::raw('(CASE WHEN invoices.payment_status	 = "PA" THEN "Paid" WHEN invoices.payment_status = "NP" THEN "Not Paid"  WHEN invoices.payment_status = "HP" THEN "Half Paid" ELSE "Cancel" END) AS payment_status'),
                'customers.name as customer_name'
            )
                ->join('customers', 'invoices.customer_id', 'customers.id')
                ->where('invoices.invoice_date', '>=', $start_date)
                ->where('invoices.invoice_date', '<=', $end_date)
                ->where('invoices.location_id', $location_id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
        } else {
            $invoices = Invoice::select(
                'invoices.*',
                DB::raw('(CASE WHEN invoices.payment_status	 = "PA" THEN "Paid" WHEN invoices.payment_status = "NP" THEN "Not Paid"  WHEN invoices.payment_status = "HP" THEN "Half Paid" ELSE "Cancel" END) AS payment_status'),
                'customers.name as customer_name'
            )
                ->join('customers', 'invoices.customer_id', 'customers.id')
                ->where('invoices.invoice_date', '>=', $start_date)
                ->where('invoices.invoice_date', '<=', $end_date)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
        }

        $data['invoices'] = $invoices;

        return Excel::download(new InvoiceExport($data), 'Reports_Invoice_' . date("YmdHis") . '.xlsx');
    }
    public function datatables(Request $request)
    {
        $active_status_id = array(2, 4);
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $location_id = $request->input('location_id');

        if (!empty($location_id)) {
            $invoices = Invoice::select(
                'invoices.*',
                DB::raw('(CASE WHEN invoices.payment_status	 = "PA" THEN "Paid" WHEN invoices.payment_status = "NP" THEN "Not Paid"  WHEN invoices.payment_status = "HP" THEN "Half Paid" ELSE "Cancel" END) AS payment_status'),
                'customers.name as customer_name'
            )
                ->join('customers', 'invoices.customer_id', 'customers.id')
                ->where('invoices.invoice_date', '>=', $start_date)
                ->where('invoices.invoice_date', '<=', $end_date)
                ->where('invoices.location_id', $location_id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
        } else {
            $invoices = Invoice::select(
                'invoices.*',
                DB::raw('(CASE WHEN invoices.payment_status	 = "PA" THEN "Paid" WHEN invoices.payment_status = "NP" THEN "Not Paid"  WHEN invoices.payment_status = "HP" THEN "Half Paid" ELSE "Cancel" END) AS payment_status'),
                'customers.name as customer_name'
            )
                ->join('customers', 'invoices.customer_id', 'customers.id')
                ->where('invoices.invoice_date', '>=', $start_date)
                ->where('invoices.invoice_date', '<=', $end_date)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
        }

        return Datatables::of($invoices)
            ->editColumn('total_price', function ($data) {
                return number_format($data->total_price, 0, ',', '.');
            })
            ->editColumn('total_service_charge', function ($data) {
                return number_format($data->total_service_charge, 0, ',', '.');
            })
            ->editColumn('total_tax_price', function ($data) {
                return number_format($data->total_tax_price, 0, ',', '.');
            })
            ->editColumn('total_paid', function ($data) {
                return number_format($data->total_paid, 0, ',', '.');
            })
            ->editColumn('total_outstanding', function ($data) {
                return number_format($data->total_price + $data->total_service_charge + $data->total_tax_price + $data->stamp_duty + $data->round_price - $data->total_paid, 0, ',', '.');
            })
            ->editColumn('start_period', function ($data) {
                $return = "";

                if(!empty($data->invoice_detail)){
                    foreach($data->invoice_detail as $invoice_detail){
                        if(!empty($invoice_detail->booking_detail)){
                            if($invoice_detail->booking_detail->booking->is_main_agreement == "Y"){
                                $return = date('j M Y', strtotime($invoice_detail->booking_detail->start_date));
                                break;
                            }else{
                                break;
                            }
                        }else{
                            break;
                        }
                    }
                }

                return $return;
            })
            ->editColumn('end_period', function ($data) {
                $return = "";

                if(!empty($data->invoice_detail)){
                    foreach($data->invoice_detail as $no => $invoice_detail){
                        if(!empty($invoice_detail->booking_detail)){
                            if($invoice_detail->booking_detail->booking->is_main_agreement == "Y"){
                                $return = date('j M Y', strtotime($invoice_detail->booking_detail->end_date));
                            }else{
                                break;
                            }
                        }else{
                            break;
                        }
                    }
                }

                return $return;
            })
            ->make(true);
    }
}
