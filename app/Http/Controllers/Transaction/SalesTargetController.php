<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Status;
use App\Models\Month;
use App\Models\SalesTarget;
use App\Models\Employee;
use App\Models\Booking;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class SalesTargetController extends Controller
{
    private $url = 'sales_target';
    private $form_id = 'sales_target_form';
    private $table_name = 'sales_targets';
    private $prefix_name = 'ST';
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

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['employee'] = $employee;
        $data['statuses'] = Status::all();
        return view('pages.transaction.sales_target.index', $data);
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
        $data['months'] = Month::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        return view('pages.transaction.sales_target.editor', $data);
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

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'year' => 'required|integer',
            'month' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $status = Status::where('name', $request['status_name'])->first();

            $sales_target = new SalesTarget;
            $sales_target->status_id = $status->id;
            $sales_target->employee_id = $request['employee_id'];
            $sales_target->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name);
            $sales_target->year = $request['year'];
            $sales_target->month = $request['month'];
            $sales_target->total_target = $request['total_target'];
            $sales_target->total_target_vo = $request['total_target_vo'];
            $sales_target->total_target_so = $request['total_target_so'];

            switch($status->action){
                case "draft" : $sales_target->draft_by = Auth::user()->name;
                break;
                case "posting" : $sales_target->posting_by = Auth::user()->name;
                break;
                case "complete" : $sales_target->complete_by = Auth::user()->name;
                break;
            }

            if($sales_target->save()){
                \Session::flash('success', 'You are success in inputing your data');
            }else{
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

        $data['url'] = $this->url;
        $data['sales_target'] = SalesTarget::findOrFail($id);
        return view('pages.transaction.sales_target.detail', $data);
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
        $data['months'] = Month::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['sales_target'] = SalesTarget::findOrFail($id);
        return view('pages.transaction.sales_target.editor', $data);
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

        $sales_target = SalesTarget::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'year' => 'required|integer',
            'month' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $status = Status::where('name', $request['status_name'])->first();

            $sales_target->status_id = $status->id;
            $sales_target->employee_id = $request['employee_id'];
            $sales_target->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name);
            $sales_target->year = $request['year'];
            $sales_target->month = $request['month'];
            $sales_target->total_target = $request['total_target'];
            $sales_target->total_target_vo = $request['total_target_vo'];
            $sales_target->total_target_so = $request['total_target_so'];

            switch($status->action){
                case "draft" : $sales_target->draft_by = Auth::user()->name;
                break;
                case "posting" : $sales_target->posting_by = Auth::user()->name;
                break;
                case "complete" : $sales_target->complete_by = Auth::user()->name;
                break;
            }

            if($sales_target->save()){
                \Session::flash('success', 'You are success in inputing your data');
            }else{
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
        $sales_target = SalesTarget::findOrFail($id);
        $status = $sales_target->status;
        if($sales_target->status->name == 'posted'){
            $status = Status::where('name', 'void')->first();
        }else if($sales_target->status->name == 'open'){
            $status = Status::where('name', 'discard')->first();
        }
        $sales_target->status_id = $status->id;
        $sales_target->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch($status->action){
            case "discard" : $sales_target->discard_by = Auth::user()->name;
            break;
            case "cancel" : $sales_target->cancel_by = Auth::user()->name;
            break;
        }

        if($sales_target->save()){
            \Session::flash('success', 'Prospect = '.$sales_target->code.' is '.$status->name);
        }else{
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

    public function datatables(){
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $sales_targets = SalesTarget::join('statuses','statuses.id','sales_targets.status_id')
            ->join('employees','employees.id','sales_targets.employee_id')
            ->select('sales_targets.*', 'employees.name as employee_name', 'statuses.name as status_name')
            ->whereIn('employees.id', $this->ids)
            ->where('sales_targets.status_id', \Request::get('status_id'))
            ->get();

        return DataTables::of($sales_targets)
            ->editColumn('month', function($data){
                $month_name = "";
                switch($data->month){
                    case 1: $month_name = "January";break;
                    case 2: $month_name = "February";break;
                    case 3: $month_name = "March";break;
                    case 4: $month_name = "April";break;
                    case 5: $month_name = "May";break;
                    case 6: $month_name = "June";break;
                    case 7: $month_name = "July";break;
                    case 8: $month_name = "August";break;
                    case 9: $month_name = "September";break;
                    case 10: $month_name = "October";break;
                    case 11: $month_name = "November";break;
                    case 12: $month_name = "December";break;
                }
                return $month_name;
            })
            ->editColumn('total_target', function ($data) {
                return number_format($data->total_target,0,',','.');
            })
            ->editColumn('total_target_vo', function ($data) {
                return number_format($data->total_target_vo,0,',','.');
            })
            ->editColumn('total_target_so', function ($data) {
                return number_format($data->total_target_so,0,',','.');
            })
            ->make(true);
    }
}
