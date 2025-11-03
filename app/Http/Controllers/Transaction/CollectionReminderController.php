<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ParameterSettingController;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\FollowUp;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Carbon\Carbon;

class CollectionReminderController extends Controller
{
    private $url = 'collection_reminder';
    private $form_id = 'collection_reminder_form';
    private $table_name = 'collection_reminder';
    private $prefix_name = 'CR';
    private $ids = array();
    private $reminder_default_month;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->reminder_default_month = ParameterSettingController::getParameter("reminder_default_month");
    }

    public function index(Request $request)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['employee'] = $employee;
        $data['location'] = Auth::user()->location;

        return view('pages.transaction.collection_reminder.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'remarks' => 'required',
            'follow_up_date' => 'required',
        ]);
        if ($validator->fails()) {
            \Session::flash('error', "You have to fill every form coloumn to input follow up");

            return Redirect::to($this->url);
        }else{
            $bookings_id = $request['booking_id'];
            $invoice_id = $request['invoice_id'];
            if($bookings_id != null){
                $follow_up_number = 1;
                $booking_follow_ups = FollowUp::Where([
                    'booking_id' => $bookings_id
                ])->get();

                $follow_up_number = $follow_up_number + sizeof($booking_follow_ups);
            }else{
                $follow_up_number = 1;
                $invoice_follow_ups = FollowUp::Where([
                    'invoice_id' => $invoice_id
                ])->get();

                $follow_up_number = $follow_up_number + sizeof($invoice_follow_ups);
            }

            DB::beginTransaction();

            $follow_up = new FollowUp;
            $follow_up->booking_id = $request['booking_id'];
            $follow_up->invoice_id = $request['invoice_id'];
            $follow_up->follow_up_number = $follow_up_number;
            $follow_up->remarks = $request['remarks'];
            $follow_up->follow_up_date = date('Y-m-d', strtotime($request['follow_up_date']));
            $follow_up->created_by = \Auth::user()->name;
            if($follow_up->save()){
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            }else{
                DB::rollBack();
                \Session::flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url);
        }
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
        $array_of_status_ids = array(2, 4);
        $location_id = $request['location_id'];

        if($location_id != null){
            $collections = Invoice::join('customers','customers.id','invoices.customer_id')
                ->join('locations','locations.id','invoices.location_id')
                ->select('invoices.*','customers.name as customer_name',
                    'customers.tax_number as customer_tax_number',
                    'locations.name as location_name')
                ->where('invoices.due_date', '>=', date('Y-m-d'))
                ->where('invoices.payment_status', 'NP')
                ->where('invoices.location_id',$location_id)
                ->whereIn('invoices.status_id', $array_of_status_ids)
                ->get();
        }else{
            $collections = Invoice::join('customers','customers.id','invoices.customer_id')
                ->join('locations','locations.id','invoices.location_id')
                ->select('invoices.*','customers.name as customer_name',
                    'customers.tax_number as customer_tax_number',
                    'locations.name as location_name')
                ->where('invoices..due_date', '>=', date('Y-m-d'))
                ->where('invoices.payment_status', 'NP')
                ->whereIn('invoices.status_id', $array_of_status_ids)
                ->get();
        }

        return DataTables::of($collections)
        ->editColumn('total_price', function ($data) {
            return number_format($data->total_price,0,',','.');
        })
        ->editColumn('total_tax_price', function ($data) {
            return number_format($data->total_tax_price,0,',','.');
        })
        ->make(true);
    }

}
