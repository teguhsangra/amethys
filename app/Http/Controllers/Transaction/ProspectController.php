<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\CustomerController;
use App\Http\Controllers\Master\ContactController;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Referral;
use App\Models\Agent;
use App\Models\Prospect;
use App\Models\NatureOfBusiness;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class ProspectController extends Controller
{
    private $url = 'prospect';
    private $form_id = 'prospect_form';
    private $table_name = 'prospects';
    private $prefix_name = 'Pros';
    private $ids = array();
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
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['employee'] = $employee;
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['months'] = array(
            [
                'name' => 'January', 'number' => '01'
            ],

            [
                'name' => 'February', 'number' => '02'
            ],

            [
                'name' => 'March', 'number' => '03'
            ],

            [
                'name' => 'April', 'number' => '04'
            ],

            [
                'name' => 'May', 'number' => '05'
            ],

            [
                'name' => 'June', 'number' => '06'
            ],

            [
                'name' => 'July', 'number' => '07'
            ],

            [
                'name' => 'August', 'number' => '08'
            ],

            [
                'name' => 'September', 'number' => '09'
            ],
            [
                'name' => 'October', 'number' => '10'
            ],
            [
                'name' => 'November', 'number' => '11'
            ],
            [
                'name' => 'December', 'number' => '12'
            ]
        );
        $data['statuses'] = Status::all();
        return view('pages.transaction.prospect.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['referrals'] = Referral::get();
        $data['agents'] = Agent::get();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        return view('pages.transaction.prospect.editor', $data);
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
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        if($request['customer_status'] == "E"){
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'customer_id' => 'required',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'customer_name' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            DB::beginTransaction();
            // Start :Logic for customer
            $customer_id = CustomerController::create_from_transaction($request);
            if($customer_id == null){
                \Session::flash('error', 'You are failed in inputing your data !!!');
                DB::rollBack();
            }
            // End :Logic for customer

            // Start :Logic for contact
            $contact_id = ContactController::create_from_transaction($request, $customer_id);
            if($contact_id == null){
                \Session::flash('error', 'You are failed in inputing your data !!!');
                DB::rollBack();
            }
            // End :Logic for contact

            $status = Status::where('name', $request['status_name'])->first();

            $prospect = new Prospect;
            $prospect->status_id = $status->id;
            $prospect->employee_id = $request['employee_id'];
            $prospect->referral_id = $request['referral_id'];
            $prospect->agent_id = $request['agent_id'];
            $prospect->customer_id = $customer_id;
            $prospect->contact_id = $contact_id;
            $prospect->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name);
            $prospect->notes = $request['notes'];
            $prospect->customer_status = $request['customer_status'];

            switch($status->action){
                case "draft" : $prospect->draft_by = Auth::user()->name;
                break;
                case "posting" : $prospect->posting_by = Auth::user()->name;
                break;
            }

            if($prospect->save()){
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
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['prospect'] = Prospect::findOrFail($id);
        return view('pages.transaction.prospect.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url.'/'.$id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['referrals'] = Referral::get();
        $data['agents'] = Agent::get();
        $data['prospect'] = Prospect::findOrFail($id);
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        return view('pages.transaction.prospect.editor', $data);
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
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $prospect = Prospect::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'customer_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            DB::beginTransaction();
            $status = Status::where('name', $request['status_name'])->first();

            $prospect->status_id = $status->id;
            $prospect->employee_id = $request['employee_id'];
            $prospect->referral_id = $request['referral_id'];
            $prospect->agent_id = $request['agent_id'];
            $prospect->notes = $request['notes'];

            switch($status->action){
                case "draft" : $prospect->draft_by = Auth::user()->name;
                break;
                case "posting" : $prospect->posting_by = Auth::user()->name;
                break;
            }

            if($prospect->save()){
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $discard_or_cancel_reason = \Request::get('discard_or_cancel_reason');
        $prospect = Prospect::findOrFail($id);

        $status = $prospect->status;
        if($prospect->status->name == 'posted'){
            $status = Status::where('name', 'void')->first();
        }else if($prospect->status->name == 'open'){
            $status = Status::where('name', 'discard')->first();
        }

        $active_status_id = array(1, 2, 4);
        if(sizeof($prospect->sales_activity->whereIn('status_id', $active_status_id)) > 0){
            \Session::flash('error', 'Prospect = '.$prospect->code." can't be ".$status->name.' because already used in other active transaction');
            return Redirect::to($this->url);
        }
        $prospect->status_id = $status->id;
        $prospect->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch($status->action){
            case "discard" : $prospect->discard_by = Auth::user()->name;
            break;
            case "cancel" : $prospect->cancel_by = Auth::user()->name;
            break;
        }

        if($prospect->save()){
            \Session::flash('success', 'Prospect = '.$prospect->code.' is '.$status->name);
        }else{
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function get_by_id($id){
        $prospect = Prospect::join('statuses','statuses.id','prospects.status_id')
            ->join('employees','employees.id','prospects.employee_id')
            ->join('customers','customers.id','prospects.customer_id')
            ->leftJoin('referrals','referrals.id','prospects.referral_id')
            ->leftJoin('agents','agents.id','prospects.agent_id')
            ->select('prospects.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name')
            ->where('prospects.id',$id)
            ->first();
        return $prospect;
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

    public function datatables(){
        $selection_employee = \Request::get('selection_employee');
        $selection_month = \Request::get('selection_month');
        $selection_year = \Request::get('selection_year');

        $employee = Employee::where('user_id',Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        if(!empty($selection_employee)){
            if(!empty($selection_month) && !empty($selection_year)){
                $first_date_of_month = date("Y-m-d", strtotime($selection_year."-".$selection_month."-01"));
                $end_date_of_month = date("Y-m-t", strtotime($first_date_of_month));
                $prospects = Prospect::join('statuses','statuses.id','prospects.status_id')
                    ->join('employees','employees.id','prospects.employee_id')
                    ->join('customers','customers.id','prospects.customer_id')
                    ->leftJoin('referrals','referrals.id','prospects.referral_id')
                    ->leftJoin('agents','agents.id','prospects.agent_id')
                    ->select('prospects.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name')
                    ->where('employees.id', $selection_employee)
                    ->where('prospects.created_at','>=',$first_date_of_month." 00:00:00")
                    ->where('prospects.created_at','<=',$end_date_of_month." 23:59:59")
                    ->where('prospects.status_id', \Request::get('status_id'))
                    ->get();
            }else{
                $prospects = Prospect::join('statuses','statuses.id','prospects.status_id')
                    ->join('employees','employees.id','prospects.employee_id')
                    ->join('customers','customers.id','prospects.customer_id')
                    ->leftJoin('referrals','referrals.id','prospects.referral_id')
                    ->leftJoin('agents','agents.id','prospects.agent_id')
                    ->select('prospects.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name')
                    ->where('employees.id', $selection_employee)
                    ->where('prospects.status_id', \Request::get('status_id'))
                    ->get();
            }
        }else{
            if(!empty($selection_month) && !empty($selection_year)){
                $first_date_of_month = date("Y-m-d", strtotime($selection_year."-".$selection_month."-01"));
                $end_date_of_month = date("Y-m-t", strtotime($first_date_of_month));
                $prospects = Prospect::join('statuses','statuses.id','prospects.status_id')
                    ->join('employees','employees.id','prospects.employee_id')
                    ->join('customers','customers.id','prospects.customer_id')
                    ->leftJoin('referrals','referrals.id','prospects.referral_id')
                    ->leftJoin('agents','agents.id','prospects.agent_id')
                    ->select('prospects.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name')
                    ->whereIn('employees.id', $this->ids)
                    ->where('prospects.created_at','>=',$first_date_of_month." 00:00:00")
                    ->where('prospects.created_at','<=',$end_date_of_month." 23:59:59")
                    ->where('prospects.status_id', \Request::get('status_id'))
                    ->get();
            }else{
                $prospects = Prospect::join('statuses','statuses.id','prospects.status_id')
                    ->join('employees','employees.id','prospects.employee_id')
                    ->join('customers','customers.id','prospects.customer_id')
                    ->leftJoin('referrals','referrals.id','prospects.referral_id')
                    ->leftJoin('agents','agents.id','prospects.agent_id')
                    ->select('prospects.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name')
                    ->whereIn('employees.id', $this->ids)
                    ->where('prospects.status_id', \Request::get('status_id'))
                    ->get();
            }
        }

        return DataTables::of($prospects)->make(true);
    }
}
