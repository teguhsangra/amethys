<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Inquiry;
use App\Models\Employee;
use App\Exports\InquiryExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Carbon\Carbon;


class InquiryController extends Controller
{

    private $url = 'inquiry_report';
    private $form_id = 'inquiry_report_form';
    private $table_name = 'inquiries';
    private $prefix_name = 'INQ';
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
    public function index()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['start_date'] = Carbon::now();
        $data['end_date'] = Carbon::now();
        return view('pages.report.inquiry.index', $data);
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
        return Excel::download(new InquiryExport($request['start_date'], $request['end_date']), 'Reports_Inquiry_' . Carbon::now()->format("j F Y") . '.xlsx');
    }
    public function get_child_of_this_employee($id)
    {
        $employee = Employee::findOrFail($id);
        if (sizeof($employee->this_child) > 0) {
            foreach ($employee->this_child as $no => $detail) {
                $this->ids[sizeof($this->ids)] = $detail->id;
                $this->get_child_of_this_employee($detail->id);
            }
        }
    }

    public function datatables(Request $request)
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');


        if ($start_date != null && $end_date != null) {
            $inquiries = Inquiry::select(
                'inquiries.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'inquiries.created_at',
                'inquiries.posting_by'
            )
                ->join('locations', 'inquiries.location_id', 'locations.id')
                ->join('customers', 'inquiries.customer_id',  'customers.id')
                ->join('contacts', 'inquiries.contact_id',  'contacts.id')
                ->where('inquiries.created_at', '>=', $start_date)
                ->where('inquiries.created_at', '<=', $end_date)
                ->get();
        } else {
            $inquiries = Inquiry::select(
                'inquiries.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'inquiries.created_at',
                'inquiries.posting_by'
            )
                ->join('locations', 'inquiries.location_id', '=', 'locations.id')
                ->join('customers', 'inquiries.customer_id', '=', 'customers.id')
                ->join('contacts', 'inquiries.contact_id', '=', 'contacts.id')
                ->get();
        }
        return Datatables::of($inquiries)->make(true);
    }
}
