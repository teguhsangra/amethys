<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Models\Location;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Room;
use App\Models\Product;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Ticketing;
use App\Models\TicketingSubject;
use App\Models\TicketingReply;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class TicketingController extends Controller
{
    private $url = 'ticketing';
    private $form_id = 'ticketing_form';
    private $ids = array();
    private $table_name = 'ticketings';
    private $prefix_name = 'TKT';

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
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        return view('pages.transaction.ticketing.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['room'] = Room::get();
        $data['products'] = Product::where('main_status', 'Y')->get();
        $data['package'] = Package::get();
        $data['subjects'] = TicketingSubject::get();

        return view('pages.transaction.ticketing.editor', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
            'customer_id' => 'required',
            'contact_id' => 'required',
            'employee_id' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            DB::beginTransaction();
            $ticketing = new Ticketing;
            $ticketing->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
            $ticketing->user_id = Auth::user()->id;
            $ticketing->location_id = $request['location_id'];
            $ticketing->customer_id =  $request['customer_id'];
            $ticketing->contact_id = $request['contact_id'];
            $ticketing->room_id = $request['room_id'];
            $ticketing->product_id = $request['product_id'];
            $ticketing->package_id = $request['package_id'];
            $ticketing->booking_id = $request['booking_id'];
            $ticketing->order_id = $request['order_id'];
            $ticketing->ticketing_subject_id = $request['ticketing_subject_id'];
            $ticketing->is_closed = 'N';
            $ticketing->subject = $request['subject'];
            $ticketing->remarks = $request['remarks'];

            if($ticketing->save()){
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
                MailController::ticketing_mail($ticketing->id);
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
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['url_reply'] = "ticketing/reply";
        $data['method'] = 'POST';
        $data['ticketing'] = Ticketing::findOrFail($id);
        $data['id'] = $id;
        return view('pages.transaction.ticketing.detail', $data);
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
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $ticketing = Ticketing::findOrFail($id);
        $ticketing->is_closed = "Y";
        if ($ticketing->save()) {
            MailController::ticketing_mail($ticketing->id);
            \Session::flash('success', 'Closed Ticketing = ' . $ticketing->code);
        } else {
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function get_child_of_this_employee($id){
        $a_g_and_module = HomeController::getAccess($this->url);

        $show_data_by_structure = false;

        if($a_g_and_module != null){
            if($a_g_and_module->showDataByStructure == 1){
                $show_data_by_structure = true;
            }
        }

        if($show_data_by_structure){
            $employee = Employee::findOrFail($id);
            if(sizeof($employee->this_child) > 0){
                foreach($employee->this_child as $no => $detail){
                    $this->ids[sizeof($this->ids)] = $detail->id;
                    $this->get_child_of_this_employee($detail->id);
                }
            }
        }else{
            $employees = Employee::where('id', '!=', $id)->get();
            foreach($employees as $detail){
                array_push($this->ids, $detail->id);
            }
        }
    }

    public function datatables()
    {
        $ticketing = Ticketing::select('ticketings.*','users.name as user',
        DB::raw('(CASE WHEN ticketings.is_closed = "Y" THEN "Closed" ELSE "Open" END) AS status')
        )->join('users','users.id','=','ticketings.user_id')
        ->get();
        return DataTables::of($ticketing)
        ->editColumn('subject', function ($data) {
            if($data->subject == null){
                return $data->ticketing_subject->name;
            }else{
                return $data->subject;
            }
        })
        ->make(true);
    }


    public function ticketing_reply(Request $request)
    {
        $ticketing_id = $request['ticketing_id'];
        $data['ticketing'] = Ticketing::findOrFail($ticketing_id);

        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'remarks' => 'required',

        ]);

        if ($validator->fails()) {
             return redirect($this->url . '/' . $ticketing_id)
                ->withErrors($validator)
                ->withInput();
        }else{

            DB::beginTransaction();
            $ticketing = new TicketingReply;
            $ticketing->user_id = $request['user_id'];
            $ticketing->ticketing_id = $request['ticketing_id'];
            $ticketing->customer_id =  $data['ticketing']->customer_id;
            $ticketing->contact_id = $data['ticketing']->contact_id;
            $ticketing->employee_id = $request['employee_id'];
            $ticketing->remarks = $request['remarks'];

            if($ticketing->save()){
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            }else{
                DB::rollBack();
                \Session::flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url . '/' . $ticketing_id);
        }
    }
}
