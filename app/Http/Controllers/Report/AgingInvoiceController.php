<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ParameterSettingController;
use App\Exports\InvoiceAgingExport;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Invoice;
use Carbon\Carbon;
use DataTables;
use Validator;
use Redirect;
use Excel;
use Auth;
use DB;

class AgingInvoiceController extends Controller
{
    private $url = 'aging_invoice_report';
    private $form_id = 'aging_invoice_report_form';
    private $table_name = 'aging_invoice_report';
    private $prefix_name = 'IAR';
    private $ids = array();
    private $penalty_invoice_total_per_day;
    private $penalty_invoice_id;
    private $tax_percentage;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->penalty_invoice_total_per_day = ParameterSettingController::getParameter("penalty_invoice_total_per_day");
        $this->tax_percentage = ParameterSettingController::getParameter("tax_percentage");
        $this->penalty_invoice_id = ParameterSettingController::getParameter("penalty_invoice_id");
    }

    public function index()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $active_status_id = array(2, 4);

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['penalty_invoice_total_per_day'] = $this->penalty_invoice_total_per_day->double_value;
        $data['today'] = date("Y-m-d");
        $data['invoices'] = Invoice::where('invoices.payment_status', 'NP')
            ->whereIn('invoices.status_id', $active_status_id)
            ->get();

        return view('pages.report.aging_invoice.index',$data);
    }

    public function exportToExcel(Request $request){
        $active_status_id = array(2, 4);
        
        $data['invoices'] = Invoice::where('invoices.payment_status', 'NP')
            ->whereIn('invoices.status_id', $active_status_id)
            ->get();

        return Excel::download(new InvoiceAgingExport($data), 'Reports_Aging_Invoice_' . date("YmdHis") . '.xlsx');

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
}
