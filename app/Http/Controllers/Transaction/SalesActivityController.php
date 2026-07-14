<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\ContactController;
use App\Http\Controllers\MailController;
use App\Models\Status;
use App\Models\SalesActivity;
use App\Models\MarketingMaterial;
use App\Models\Prospect;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Employee;
use DataTables;
use Validator;
use Redirect;
use Mail;
use Auth;
use DB;

class SalesActivityController extends Controller
{
    private $url = 'sales_activity';
    private $form_id = 'sales_activity_form';
    private $table_name = 'sales_activities';
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['statuses'] = Status::all();
        return view('pages.transaction.sales_activity.index', $data);
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

        $data['sales_activites'] = SalesActivity::join('prospects', 'prospects.id', 'sales_activities.prospect_id')
            ->whereIn('sales_activities.status_id', $active_status_id)
            ->where('prospects.employee_id', $employee->id)
            ->get();

        $data['prospects'] = Prospect::whereIn('employee_id', $this->ids)
            ->whereIn('status_id', $active_status_id)
            ->get();

        $data['customers'] = Customer::get();
        $data['marketing_materials'] = MarketingMaterial::get();

        return view('pages.transaction.sales_activity.editor', $data);
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
            'source_status' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $status = Status::where('name', $request['status_name'])->first();

            $prospect_id = null;
            $customer_id = null;
            $contact_id = null;

            // Start :Logic for prospect & previous
            if ($request['source_status'] == "prospect") {
                $prospect = Prospect::findOrFail($request['prospect_id']);
                $customer_id = $prospect->customer_id;
            } else if ($request['source_status'] == "previous_activity") {
                $previous = SalesActivity::findOrFail($request['previous_id']);
                $customer_id = $previous->customer_id;
            } else {
                $customer_id = $request['customer_id'];
            }
            // End :Logic for prospect & previous

            // Start :Logic for contact
            $contact_id = ContactController::create_from_transaction($request, $customer_id);
            if($contact_id == null){
                \Session::flash('error', 'You are failed in inputing your data !!!');
                DB::rollBack();
            }
            // End :Logic for contact

            $sales_activity = new SalesActivity;
            $sales_activity->status_id = $status->id;
            $sales_activity->prospect_id = $request['prospect_id'];
            $sales_activity->previous_id = $request['previous_id'];
            $sales_activity->customer_id = $customer_id;
            $sales_activity->contact_id = $contact_id;
            $sales_activity->employee_id = $employee->id;
            $sales_activity->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name);
            $sales_activity->source_status = $request['source_status'];
            $sales_activity->type = $request['type'];
            $sales_activity->location = $request['location'];
            $sales_activity->notes = $request['notes'];

            switch ($status->action) {
                case "draft":
                    $sales_activity->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $sales_activity->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $sales_activity->complete_by = Auth::user()->name;
                    break;
            }

            if ($sales_activity->save()) {
                if (!empty($request['marketing_material_id'])) {
                    for ($i = 0; $i < sizeof($request['marketing_material_id']); $i++) {
                        $sales_activity->marketing_material()->attach($request['marketing_material_id'][$i]);
                    }
                }
                \Session::flash('success', 'You are success in inputing your data');
            } else {
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
        $data['url'] = $this->url;
        $data['print_url'] = $this->url . '/print/' . $id;
        $data['email_url'] = $this->url . '/email/' . $id;
        $data['sales_activity'] = SalesActivity::findOrFail($id);
        return view('pages.transaction.sales_activity.detail', $data);
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
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';

        $data['sales_activites'] = SalesActivity::join('prospects', 'prospects.id', 'sales_activities.prospect_id')
            ->whereIn('sales_activities.status_id', $active_status_id)
            ->where('prospects.employee_id', $employee->id)
            ->get();
        $data['prospects'] = Prospect::whereIn('employee_id', $this->ids)
            ->whereIn('status_id', $active_status_id)
            ->get();
        $data['customers'] = Customer::get();
        $data['marketing_materials'] = MarketingMaterial::get();

        $data['sales_activity'] = SalesActivity::findOrFail($id);

        return view('pages.transaction.sales_activity.editor', $data);
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $sales_activity = SalesActivity::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $status = Status::where('name', $request['status_name'])->first();

            $prospect_id = $sales_activity->prospect_id;
            $customer_id = $sales_activity->customer_id;
            $contact_id = $sales_activity->contact_id;

            // Start :Logic for prospect & previous
            if ($request['source_status'] == "prospect") {
                $prospect = Prospect::findOrFail($request['prospect_id']);
                $customer_id = $prospect->customer_id;
            } else if ($request['source_status'] == "previous_activity") {
                $previous = SalesActivity::findOrFail($request['previous_id']);
                $customer_id = $previous->customer_id;
            } else {
                $customer_id = $request['customer_id'];
            }
            // End :Logic for prospect & previous

            // Start :Logic for contact
            $contact_id = ContactController::create_from_transaction($request, $customer_id);
            if($contact_id == null){
                \Session::flash('error', 'You are failed in inputing your data !!!');
                DB::rollBack();
            }
            // End :Logic for contact

            $sales_activity->status_id = $status->id;
            $sales_activity->prospect_id = $request['prospect_id'];
            $sales_activity->previous_id = $request['previous_id'];
            $sales_activity->customer_id = $customer_id;
            $sales_activity->contact_id = $contact_id;
            $sales_activity->employee_id = $employee->id;
            $sales_activity->source_status = $request['source_status'];
            $sales_activity->type = $request['type'];
            $sales_activity->location = $request['location'];
            $sales_activity->notes = $request['notes'];

            switch ($status->action) {
                case "draft":
                    $sales_activity->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $sales_activity->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $sales_activity->complete_by = Auth::user()->name;
                    break;
            }

            switch ($status->action) {
                case "draft":
                    $sales_activity->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $sales_activity->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $sales_activity->complete_by = Auth::user()->name;
                    break;
            }

            if ($sales_activity->save()) {
                DB::table('s_a_and_m_m')->where('sales_activity_id', $id)->delete();
                if (!empty($request['marketing_material_id'])) {
                    for ($i = 0; $i < sizeof($request['marketing_material_id']); $i++) {
                        $sales_activity->marketing_material()->attach($request['marketing_material_id'][$i]);
                    }
                }
                \Session::flash('success', 'You are success in inputing your data');
            } else {
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $discard_or_cancel_reason = \Request::get('discard_or_cancel_reason');
        $sales_activity = SalesActivity::findOrFail($id);
        $status = $sales_activity->status;
        if ($sales_activity->status->name == 'posted') {
            $status = Status::where('name', 'void')->first();
        } else if ($sales_activity->status->name == 'open') {
            $status = Status::where('name', 'discard')->first();
        }
        $sales_activity->status_id = $status->id;
        $sales_activity->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch ($status->action) {
            case "discard":
                $sales_activity->discard_by = Auth::user()->name;
                break;
            case "cancel":
                $sales_activity->cancel_by = Auth::user()->name;
                break;
        }

        if ($sales_activity->save()) {
            \Session::flash('success', 'Prospect = ' . $sales_activity->code . ' is ' . $status->name);
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $sales_activities = SalesActivity::join('statuses', 'statuses.id', 'sales_activities.status_id')
            ->leftJoin('prospects', 'prospects.id', 'sales_activities.prospect_id')
            ->leftJoin('sales_activities as previous', 'previous.id', 'sales_activities.previous_id')
            ->join('employees', 'employees.id', 'sales_activities.employee_id')
            ->join('customers', 'customers.id', 'sales_activities.customer_id')
            ->join('contacts', 'contacts.id', 'sales_activities.contact_id')
            ->select('sales_activities.*', 'statuses.name as status_name', 'prospects.code as prospect_code', 'previous.code as previous_code', 'employees.id as employee_id', 'customers.name as customer_name', 'contacts.name as contact_name')
            ->whereIn('employees.id', $this->ids)
            ->where('sales_activities.status_id', \Request::get('status_id'))
            ->get();

        return DataTables::of($sales_activities)->make(true);
    }

    public function print($id)
    {
        $data['sales_activity'] = SalesActivity::findOrFail($id);

        return view('pages.transaction.sales_activity.print', $data);
    }

    public function sendEmail(Request $request, $id)
    {
        $data['sales_activity'] = SalesActivity::findOrFail($id);
        $data['text'] = "Offering";

        Mail::send('pages.transaction.sales_activity.mail', $data, function ($message) use ($data) {
            $message->from('info@rakitek.com', 'rakitek');

            $message->to($data['sales_activity']->customer->email)->subject('Offering');
        });

        if (Mail::failures()) {
            \Session::flash('error', 'Failed');
        } else {
            \Session::flash('success', 'successfully sent an email');
        }

        return Redirect::to($this->url);
    }

    public static function create_by_inquiry($inquiry)
    {
        $return = true;

        $sales_activity = new SalesActivity;
        $sales_activity->status_id = $inquiry->status_id;
        $sales_activity->inquiry_id = $inquiry->id;
        $sales_activity->customer_id = $inquiry->customer_id;
        $sales_activity->contact_id = $inquiry->contact_id;
        $sales_activity->employee_id = $inquiry->employee_id;
        $sales_activity->code = HomeController::getTransactionCode('sales_activities', 'ST');
        $sales_activity->source_status = "existing_customer";
        $sales_activity->type = "offering";
        $sales_activity->location = $inquiry->location->name;
        $sales_activity->notes = $inquiry->remarks;

        if ($sales_activity->save()) { } else {
            $return = false;
        }
        return $return;
    }

    public static function create_by_booking($booking)
    {
        $return = true;

        $sales_activity = new SalesActivity;
        $sales_activity->status_id = $booking->status_id;
        $sales_activity->booking_id = $booking->id;
        $sales_activity->customer_id = $booking->customer_id;
        $sales_activity->contact_id = $booking->contact_id;
        $sales_activity->employee_id = $booking->employee_id;
        $sales_activity->code = HomeController::getTransactionCode('sales_activities', 'ST');
        $sales_activity->source_status = "existing_customer";
        $sales_activity->type = "dealing";
        $sales_activity->location = $booking->location->name;
        $sales_activity->notes = $booking->remarks;

        if ($sales_activity->save()) { } else {
            $return = false;
        }
        return $return;
    }
}
