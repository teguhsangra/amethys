<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Status;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\BankAccount;
use App\Models\NonCash;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\PaymentAllocation;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class DepositController extends Controller
{
    private $url = 'deposit';
    private $form_id = 'deposit_form';
    private $table_name = 'deposits';
    private $prefix_name = 'DEP';
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
        return view('pages.transaction.deposit.index', $data);
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
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['non_cashes'] = NonCash::get();
        $data['bank_accounts'] = BankAccount::get();

        return view('pages.transaction.deposit.editor', $data);
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
            'location_id' => 'required',
            'customer_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $deposit = new Deposit;
            $deposit->status_id = $status->id;
            $deposit->location_id = $request['location_id'];
            $deposit->customer_id = $request['customer_id'];
            $deposit->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
            $deposit->category = $request['category'];
            $deposit->type_security_deposit = $request['type_security_deposit'];
            $deposit->total_deposit = $request['total_deposit'];

            if(!empty($request['due_date'])){
                $deposit->due_date = date('Y-m-d', strtotime($request['due_date']));
            }

            $deposit->remarks = $request['remarks'];

            switch($status->action){
                case "draft" : $deposit->draft_by = Auth::user()->name;
                break;
                case "posting" : $deposit->posting_by = Auth::user()->name;
                break;
                case "complete" : $deposit->complete_by = Auth::user()->name;
                break;
            }

            if($deposit->save()){
                // Start : Logic for Payment On Deposit
                if($request['type_security_deposit'] == 'OUT'){
                    $payment = new Payment;
                    $payment->status_id = 2;
                    $payment->location_id = $request['location_id'];
                    $payment->customer_id = $request['customer_id'];
                    $payment->code = HomeController::getTransactionCode('payments', 'PAY', $request['location_id']);
                    $payment->total_payment = $deposit->total_deposit;
                    $payment->payment_date = date('Y-m-d');
                    $payment->posting_by = Auth::user()->name;
                    if($payment->save()){
                        for($i = 0; $i < sizeof($request['payment_amount']); $i++){
                            $payment_detail = new PaymentDetail;
                            $payment_detail->payment_id = $payment->id;
                            $payment_detail->bank_account_id = $request['payment_bank_account_id'][$i];
                            $payment_detail->non_cash_id = $request['payment_non_cash_id'][$i];
                            $payment_detail->payment_type = $request['payment_type'][$i];
                            $payment_detail->amount = $request['payment_amount'][$i];
                            $payment_detail->bank_issuer = $request['payment_bank_issuer'][$i];
                            $payment_detail->account_number = $request['payment_account_number'][$i];
                            $payment_detail->account_name = $request['payment_account_name'][$i];
                            $payment_detail->card_type = $request['payment_card_type'][$i];
                            $payment_detail->card_holder_name = $request['payment_card_holder_name'][$i];
                            $payment_detail->card_number = $request['payment_card_number'][$i];
                            $payment_detail->batch = $request['payment_batch'][$i];
                            $payment_detail->description = $request['payment_description'][$i];
                            if(!$payment_detail->save()){
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            }
                        }

                        $payment_allocation = new PaymentAllocation;
                        $payment_allocation->payment_id = $payment->id;
                        $payment_allocation->deposit_id = $deposit->id;
                        $payment_allocation->total_need = $deposit->total_deposit;
                        $payment_allocation->payment_allocation = $deposit->total_deposit;
                        if(!$payment_allocation->save()){
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }

                        $deposit->status_id = 2;
                        $deposit->category = "clearing_deposit";
                        $deposit->posting_by = Auth::user()->name;
                        $deposit->payment_status = 'PA';
                        if($deposit->save()){
                            $customer = $deposit->customer;
                            $customer->total_security_deposit = $customer->total_security_deposit - $deposit->total_deposit;
                            if(!$customer->save()){
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            }
                        }else{
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    }else{
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Logic for Payment On Deposit

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
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['print_url'] = $this->url.'/print/'.$id;
        $data['deposit'] = Deposit::findOrFail($id);

        return view('pages.transaction.deposit.detail', $data);
    }

    public function print($id){
        $data['deposit'] = Deposit::findOrFail($id);
        return view('pages.transaction.deposit.print', $data);
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
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url.'/'.$id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['non_cashes'] = NonCash::get();
        $data['bank_accounts'] = BankAccount::get();
        $data['deposit'] = Deposit::findOrFail($id);

        return view('pages.transaction.deposit.editor', $data);
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
        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
            'customer_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $deposit = Deposit::findOrFail($id);
            $deposit->status_id = $status->id;
            $deposit->location_id = $request['location_id'];
            $deposit->customer_id = $request['customer_id'];
            $deposit->category = $request['category'];
            $deposit->type_security_deposit = $request['type_security_deposit'];
            $deposit->total_deposit = $request['total_deposit'];

            if(!empty($request['due_date'])){
                $deposit->due_date = date('Y-m-d', strtotime($request['due_date']));
            }

            $deposit->remarks = $request['remarks'];

            switch($status->action){
                case "draft" : $deposit->draft_by = Auth::user()->name;
                break;
                case "posting" : $deposit->posting_by = Auth::user()->name;
                break;
                case "complete" : $deposit->complete_by = Auth::user()->name;
                break;
            }

            if($deposit->save()){
                // Start : Logic for Payment On Deposit
                if($request['type_security_deposit'] == 'OUT'){
                    $payment = new Payment;
                    $payment->status_id = 2;
                    $payment->location_id = $request['location_id'];
                    $payment->customer_id = $request['customer_id'];
                    $payment->code = HomeController::getTransactionCode('payments', 'PAY', $request['location_id']);
                    $payment->total_payment = $deposit->total_deposit;
                    $payment->payment_date = date('Y-m-d');
                    $payment->posting_by = Auth::user()->name;
                    if($payment->save()){
                        for($i = 0; $i < sizeof($request['payment_amount']); $i++){
                            $payment_detail = new PaymentDetail;
                            $payment_detail->payment_id = $payment->id;
                            $payment_detail->bank_account_id = $request['payment_bank_account_id'][$i];
                            $payment_detail->non_cash_id = $request['payment_non_cash_id'][$i];
                            $payment_detail->payment_type = $request['payment_type'][$i];
                            $payment_detail->amount = $request['payment_amount'][$i];
                            $payment_detail->bank_issuer = $request['payment_bank_issuer'][$i];
                            $payment_detail->account_number = $request['payment_account_number'][$i];
                            $payment_detail->account_name = $request['payment_account_name'][$i];
                            $payment_detail->card_type = $request['payment_card_type'][$i];
                            $payment_detail->card_holder_name = $request['payment_card_holder_name'][$i];
                            $payment_detail->card_number = $request['payment_card_number'][$i];
                            $payment_detail->batch = $request['payment_batch'][$i];
                            $payment_detail->description = $request['payment_description'][$i];
                            if(!$payment_detail->save()){
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in updating your data !!!');
                            }
                        }

                        $payment_allocation = new PaymentAllocation;
                        $payment_allocation->payment_id = $payment->id;
                        $payment_allocation->deposit_id = $deposit->id;
                        $payment_allocation->total_need = $deposit->total_deposit;
                        $payment_allocation->payment_allocation = $deposit->total_deposit;
                        if(!$payment_allocation->save()){
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in updating your data !!!');
                        }

                        $deposit->status_id = 2;
                        $deposit->category = "clearing_deposit";
                        $deposit->posting_by = Auth::user()->name;
                        $deposit->payment_status = 'PA';
                        if($deposit->save()){
                            $customer = $deposit->customer;
                            $customer->total_security_deposit = $customer->total_security_deposit - $deposit->total_deposit;
                            if(!$customer->save()){
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in updating your data !!!');
                            }
                        }else{
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in updating your data !!!');
                        }
                    }else{
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in updating your data !!!');
                    }
                }
                // End : Logic for Payment On Deposit

                DB::commit();
                \Session::flash('success', 'You are success in updating your data');
            }else{
                DB::rollBack();
                \Session::flash('error', 'You are failed in updating your data !!!');
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
        $deposit = Deposit::findOrFail($id);

        $status = $deposit->status;
        if($deposit->status->name == 'posted'){
            $status = Status::where('name', 'void')->first();
        }else if($deposit->status->name == 'open'){
            $status = Status::where('name', 'discard')->first();
        }

        $active_status_id = array(1, 2, 4);
        if(sizeof($deposit->payment_allocation) > 0){
            foreach($deposit->payment_allocation as $payment_allocation){
                $payment = $payment_allocation->payment;
                if($payment->status_id == 1 || $payment->status_id == 2 || $payment->status_id == 4){
                    \Session::flash('error', 'Deposit = '.$deposit->code." can't be ".$status->name.' because already used in other active transaction');
                    return Redirect::to($this->url);
                }
            }
        }
        $deposit->status_id = $status->id;
        $deposit->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch($status->action){
            case "discard" : $deposit->discard_by = Auth::user()->name;
            break;
            case "cancel" : $deposit->cancel_by = Auth::user()->name;
            break;
        }

        if($deposit->save()){
            \Session::flash('success', 'Deposit = '.$deposit->code.' is '.$status->name);
        }else{
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function get_by_param($location_id, $customer_id, $payment_status){
        $active_status_id = array(1, 2);

        return Deposit::where('location_id', $location_id)
            ->where('customer_id', $customer_id)
            ->where('payment_status', '!=', $payment_status)
            ->whereIn('status_id', $active_status_id)
            ->get();
    }

    public function datatables(){
        $deposits = Deposit::join('statuses','statuses.id','deposits.status_id')
            ->join('customers','customers.id','deposits.customer_id')
            ->select('deposits.*', 'statuses.name as status_name', 'customers.name as customer_name')
            ->where('deposits.status_id', \Request::get('status_id'))
            ->get();

        return DataTables::of($deposits)
            ->editColumn('total_deposit', function ($data) {
                return number_format($data->total_deposit,0,',','.');
            })
            ->editColumn('category', function ($data){
                $return = "";
                switch($data->category){
                    case "security_deposit" :
                        $return = "Security Deposit";
                    break;
                    case "customer_credit" :
                        $return = "Customer Credit";
                    break;
                    case "booking_fee" :
                        $return = "Booking Fee";
                    break;
                    case "down_payment" :
                        $return = "Down Payment";
                    break;
                }
                return $return;
            })
            ->make(true);
    }

    public function get_by_id($id){
        return Deposit::findOrFail($id);
    }

    public function get_deposit_by_customer(Request $request, $customer_id){
        $array_of_bf_dp = array('booking_fee', 'down_payment');
        $active_status_id = array(1, 2, 4);
        $array_of_used_deposit_id = array();
        $proforma_id = $request['proforma_id'];
        $invoice_id = $request['invoice_id'];
        $customer_id = $request['customer_id'];

        if(!empty($proforma_id)){
            $proforma_and_deposit = Deposit::join('proforma_and_deposit', 'proforma_and_deposit.deposit_id', 'deposits.id')
                ->join('proformas', 'proformas.id', 'proforma_and_deposit.proforma_id')
                ->select('deposits.*')
                ->where('deposits.payment_status', 'PA')
                ->where('proformas.id', '!=', $proforma_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();
        }else{
            $proforma_and_deposit = Deposit::join('proforma_and_deposit', 'proforma_and_deposit.deposit_id', 'deposits.id')
                ->join('proformas', 'proformas.id', 'proforma_and_deposit.proforma_id')
                ->select('deposits.*')
                ->where('deposits.payment_status', 'PA')
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();
        }

        if(!empty($invoice_id)){
            $invoice_and_deposit = Deposit::join('invoice_and_deposit', 'invoice_and_deposit.deposit_id', 'deposits.id')
                ->join('invoices', 'invoices.id', 'invoice_and_deposit.invoice_id')
                ->select('deposits.*')
                ->where('deposits.payment_status', 'PA')
                ->where('invoices.id', '!=', $invoice_id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
        }else{
            $invoice_and_deposit = Deposit::join('invoice_and_deposit', 'invoice_and_deposit.deposit_id', 'deposits.id')
                ->join('invoices', 'invoices.id', 'invoice_and_deposit.invoice_id')
                ->select('deposits.*')
                ->where('deposits.payment_status', 'PA')
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
        }

        $applied_deposits = $proforma_and_deposit->merge($invoice_and_deposit);
        foreach($applied_deposits as $detail){
            array_push($array_of_used_deposit_id, $detail->id);
        }

        $booking_fee_and_down_payment = Deposit::where('payment_status', 'PA')
            ->where('customer_id', $customer_id)
            ->whereIn('category', $array_of_bf_dp)
            // ->whereNotIn('id', $array_of_used_deposit_id)
            ->get();
        
        $security_deposit = Deposit::where('payment_status', '!=', 'PA')
            ->where('customer_id', $customer_id)
            ->where('status_id', 2)
            ->where('category', 'security_deposit')
            // ->whereNotIn('id', $array_of_used_deposit_id)
            ->get();
        
        $available_deposits = $booking_fee_and_down_payment->merge($security_deposit);

        return $available_deposits;
    }
}
