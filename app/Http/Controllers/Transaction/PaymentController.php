<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ParameterSetting;
use App\Models\Status;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\NonCash;
use App\Models\BankAccount;
use App\Models\Invoice;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\PaymentAllocation;
use App\Models\Notification;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class PaymentController extends Controller
{
    private $url = 'payment';
    private $form_id = 'payment_form';
    private $table_name = 'payments';
    private $prefix_name = 'PAY';
    private $destinationPath = '/uploads/payment/';
    private $ids = array();
    protected $main_path;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $parameter_of_main_path = ParameterSetting::where('name','main_path')->first();
        $this->main_path = $parameter_of_main_path->string_value;
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
        return view('pages.transaction.payment.index', $data);
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

        return view('pages.transaction.payment.editor', $data);
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
            'payment_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $payment = new Payment;
            $payment->status_id = $status->id;
            $payment->location_id = $request['location_id'];
            $payment->customer_id = $request['customer_id'];
            $payment->code = HomeController::getTransactionCode('payments', 'PAY', $request['location_id']);
            $payment->payment_date = date('Y-m-d', strtotime($request['payment_date']));
            $payment->total_payment = $request['total_payment'];
            $payment->total_not_allocate = $request['total_not_allocate'];
            $payment->remarks = $request['remarks'];

            $with_holding_tax = $request->file('with_holding_tax');
            if ($request->hasFile('with_holding_tax')){
                if($request->file('with_holding_tax')->getSize() > 2000000){
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url.'/create');
                }
                if($this->main_path == "local"){
                    $path = public_path($this->destinationPath);
                }else{
                    $path = $this->main_path.$this->destinationPath;
                }

                HomeController::check_exist_folder($path);

                $extension = $with_holding_tax->getClientOriginalExtension();
                $filename = time() . '_with_holding_tax.' . $extension;
                $with_holding_tax->move($path, $filename);
                $payment->with_holding_tax = $this->destinationPath.$filename;
            }

            $other_doc_1 = $request->file('other_doc_1');
            if ($request->hasFile('other_doc_1')){
                if($request->file('other_doc_1')->getSize() > 2000000){
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url.'/create');
                }
                if($this->main_path == "local"){
                    $path = public_path($this->destinationPath);
                }else{
                    $path = $this->main_path.$this->destinationPath;
                }

                HomeController::check_exist_folder($path);

                $extension = $other_doc_1->getClientOriginalExtension();
                $filename = time() . '_other_doc_1.' . $extension;
                $other_doc_1->move($path, $filename);
                $payment->other_doc_1 = $this->destinationPath.$filename;
            }

            $other_doc_2 = $request->file('other_doc_2');
            if ($request->hasFile('other_doc_2')){
                if($request->file('other_doc_2')->getSize() > 2000000){
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url.'/create');
                }
                if($this->main_path == "local"){
                    $path = public_path($this->destinationPath);
                }else{
                    $path = $this->main_path.$this->destinationPath;
                }

                HomeController::check_exist_folder($path);

                $extension = $other_doc_2->getClientOriginalExtension();
                $filename = time() . '_other_doc_2.' . $extension;
                $other_doc_2->move($path, $filename);
                $payment->other_doc_2 = $this->destinationPath.$filename;
            }

            $other_doc_3 = $request->file('other_doc_3');
            if ($request->hasFile('other_doc_3')){
                if($request->file('other_doc_3')->getSize() > 2000000){
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url.'/create');
                }
                if($this->main_path == "local"){
                    $path = public_path($this->destinationPath);
                }else{
                    $path = $this->main_path.$this->destinationPath;
                }

                HomeController::check_exist_folder($path);

                $extension = $other_doc_3->getClientOriginalExtension();
                $filename = time() . '_other_doc_3.' . $extension;
                $other_doc_3->move($path, $filename);
                $payment->other_doc_3 = $this->destinationPath.$filename;
            }

            switch($status->action){
                case "draft" : $payment->draft_by = Auth::user()->name;
                break;
                case "posting" : $payment->posting_by = Auth::user()->name;
                break;
                case "complete" : $payment->complete_by = Auth::user()->name;
                break;
            }

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

                for($i = 0; $i < sizeof($request['payment_allocation']); $i++){
                    $payment_allocation = new PaymentAllocation;
                    $payment_allocation->payment_id = $payment->id;
                    $payment_allocation->invoice_id = $request['invoice_id'][$i];
                    $payment_allocation->deposit_id = $request['deposit_id'][$i];
                    $payment_allocation->total_need = $request['total_need'][$i];
                    $payment_allocation->payment_allocation = $request['payment_allocation'][$i];
                    if(!$payment_allocation->save()){
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }

                self::execute_payment($payment->id);

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
        $data['form_url'] = $this->url.'/complete/'.$id;
        $data['print_url'] = $this->url.'/print/'.$id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';

        $data['payment'] = Payment::findOrFail($id);

        return view('pages.transaction.payment.detail', $data);
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

        $payment = Payment::findOrFail($id);

        if ($payment->status_id != 1) {
            \Session::flash('warning', 'Sorry, you can not use payment no = ' . $payment->code . ', because this payment status = ' . $payment->status->name . ' !!!');
            return Redirect::to($this->url);
        }

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url.'/'.$id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['non_cashes'] = NonCash::get();
        $data['bank_accounts'] = BankAccount::get();
        $data['payment'] = $payment;

        return view('pages.transaction.payment.editor', $data);
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
            'payment_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $payment = Payment::findOrFail($id);
            $payment->status_id = $status->id;
            $payment->location_id = $request['location_id'];
            $payment->customer_id = $request['customer_id'];
            $payment->payment_date = date('Y-m-d', strtotime($request['payment_date']));
            $payment->total_payment = $request['total_payment'];
            $payment->total_not_allocate = $request['total_not_allocate'];
            $payment->remarks = $request['remarks'];

            $with_holding_tax = $request->file('with_holding_tax');
            if ($request->hasFile('with_holding_tax')){
                $delete_path = null;
                if($request->file('with_holding_tax')->getSize() > 2000000){
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url.'/create');
                }
                if($this->main_path == "local"){
                    $path = public_path($this->destinationPath);
                    if($payment->with_holding_tax != null){
                        $delete_path = public_path($payment->with_holding_tax);
                    }
                }else{
                    $path = $this->main_path.$this->destinationPath;
                    if($payment->with_holding_tax != null){
                        $delete_path = $this->main_path.$payment->with_holding_tax;
                    }
                }

                HomeController::check_exist_folder($path);

                if($delete_path != null){
                    \File::Delete($delete_path);
                }

                $extension = $with_holding_tax->getClientOriginalExtension();
                $filename = time() . '_with_holding_tax.' . $extension;
                $with_holding_tax->move($path, $filename);
                $payment->with_holding_tax = $this->destinationPath.$filename;
            }

            $other_doc_1 = $request->file('other_doc_1');
            if ($request->hasFile('other_doc_1')){
                $delete_path = null;
                if($request->file('other_doc_1')->getSize() > 2000000){
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url.'/create');
                }
                if($this->main_path == "local"){
                    $path = public_path($this->destinationPath);
                    if($payment->other_doc_1 != null){
                        $delete_path = public_path($payment->other_doc_1);
                    }
                }else{
                    $path = $this->main_path.$this->destinationPath;
                    if($payment->other_doc_1 != null){
                        $delete_path = $this->main_path.$payment->other_doc_1;
                    }
                }

                HomeController::check_exist_folder($path);

                if($delete_path != null){
                    \File::Delete($delete_path);
                }

                $extension = $other_doc_1->getClientOriginalExtension();
                $filename = time() . '_other_doc_1.' . $extension;
                $other_doc_1->move($path, $filename);
                $payment->other_doc_1 = $this->destinationPath.$filename;
            }


            $other_doc_2 = $request->file('other_doc_2');
            if ($request->hasFile('other_doc_2')){
                $delete_path = null;
                if($request->file('other_doc_2')->getSize() > 2000000){
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url.'/create');
                }
                if($this->main_path == "local"){
                    $path = public_path($this->destinationPath);
                    if($payment->other_doc_2 != null){
                        $delete_path = public_path($payment->other_doc_2);
                    }
                }else{
                    $path = $this->main_path.$this->destinationPath;
                    if($payment->other_doc_2 != null){
                        $delete_path = $this->main_path.$payment->other_doc_2;
                    }
                }

                HomeController::check_exist_folder($path);

                if($delete_path != null){
                    \File::Delete($delete_path);
                }

                $extension = $other_doc_2->getClientOriginalExtension();
                $filename = time() . '_other_doc_2.' . $extension;
                $other_doc_2->move($path, $filename);
                $payment->other_doc_2 = $this->destinationPath.$filename;
            }


            $other_doc_3 = $request->file('other_doc_3');
            if ($request->hasFile('other_doc_3')){
                $delete_path = null;
                if($request->file('other_doc_3')->getSize() > 2000000){
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url.'/create');
                }
                if($this->main_path == "local"){
                    $path = public_path($this->destinationPath);
                    if($payment->other_doc_3 != null){
                        $delete_path = public_path($payment->other_doc_3);
                    }
                }else{
                    $path = $this->main_path.$this->destinationPath;
                    if($payment->other_doc_3 != null){
                        $delete_path = $this->main_path.$payment->other_doc_3;
                    }
                }

                HomeController::check_exist_folder($path);

                if($delete_path != null){
                    \File::Delete($delete_path);
                }

                $extension = $other_doc_3->getClientOriginalExtension();
                $filename = time() . '_other_doc_3.' . $extension;
                $other_doc_3->move($path, $filename);
                $payment->other_doc_3 = $this->destinationPath.$filename;
            }


            switch($status->action){
                case "draft" : $payment->draft_by = Auth::user()->name;
                break;
                case "posting" : $payment->posting_by = Auth::user()->name;
                break;
                case "complete" : $payment->complete_by = Auth::user()->name;
                break;
            }

            if($payment->save()){
                DB::table('payment_details')->where('payment_id', $id)->delete();
                DB::table('payment_allocations')->where('payment_id', $id)->delete();
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

                for($i = 0; $i < sizeof($request['payment_allocation']); $i++){
                    $payment_allocation = new PaymentAllocation;
                    $payment_allocation->payment_id = $payment->id;
                    $payment_allocation->invoice_id = $request['invoice_id'][$i];
                    $payment_allocation->deposit_id = $request['deposit_id'][$i];
                    $payment_allocation->total_need = $request['total_need'][$i];
                    $payment_allocation->payment_allocation = $request['payment_allocation'][$i];
                    if(!$payment_allocation->save()){
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }

                self::execute_payment($payment->id);

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
        $payment = Payment::findOrFail($id);
        $status = $payment->status;
        if($payment->status->name == 'posted'){
            $status = Status::where('name', 'void')->first();
        }else if($payment->status->name == 'open'){
            $status = Status::where('name', 'discard')->first();
        }
        $payment->status_id = $status->id;
        $payment->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch($status->action){
            case "discard" : $payment->discard_by = Auth::user()->name;
            break;
            case "cancel" : $payment->cancel_by = Auth::user()->name;
            break;
        }

        if($payment->save()){
            \Session::flash('success', 'Prospect = '.$payment->code.' is '.$status->name);
        }else{
            \Session::flash('error', 'Failed');
        }

        self::execute_payment($id);

        return Redirect::to($this->url);
    }

    public static function execute_payment($id){
        $payment = Payment::findOrFail($id);

        switch($payment->status->name){
            case "posted" :
                foreach($payment->payment_allocation as $payment_allocation){
                    $payment_status = 'NP';
                    if($payment_allocation->payment_allocation < $payment_allocation->total_need){
                        $payment_status = 'HP';
                    }

                    if($payment_allocation->payment_allocation == $payment_allocation->total_need){
                        $payment_status = 'PA';
                    }

                    if($payment_allocation->invoice_id != null){
                        $invoice = $payment_allocation->invoice;
                        $invoice->total_paid = $invoice->total_paid + $payment_allocation->payment_allocation;
                        $invoice->payment_status = $payment_status;

                        if($invoice->save()){
                            if($invoice->booking_id != null){
                                $booking = $invoice->booking;
                                $booking->payment_status = $payment_status;
                                // $booking->total_paid = $booking->total_paid + $payment_allocation->payment_allocation;
                                $booking->save();
                            }
                            if($invoice->order_id != null){
                                $order = $invoice->order;
                                $order->payment_status = $payment_status;
                                // $order->total_paid = $order->total_paid + $payment_allocation->payment_allocation;
                                $order->save();
                            }
                            foreach($invoice->invoice_detail as $invoice_detail){
                                if($invoice_detail->booking_detail_id != null){
                                    $booking = $invoice_detail->booking_detail->booking;
                                    $booking->payment_status = $payment_status;
                                    $booking->save();
                                }
                                if($invoice_detail->order_detail_id != null){
                                    $order = $invoice_detail->order_detail->order;
                                    $order->payment_status = $payment_status;
                                    $order->save();
                                }
                            }

                            Notification::where('header', 'Unpaid Invoice No = ' . $invoice->code)->update(['read_status' => 'Y']);
                        }
                    }

                    if($payment_allocation->deposit_id != null){
                        $deposit = $payment_allocation->deposit;
                        $deposit->total_paid = $deposit->total_paid + $payment_allocation->payment_allocation;
                        $deposit->payment_status = $payment_status;

                        if($deposit->save()){
                            $customer = $deposit->customer;
                            $customer->total_security_deposit = $customer->total_security_deposit + $payment_allocation->payment_allocation;
                            $customer->save();
                        }
                    }
                }
                foreach($payment->payment_detail as $payment_detail){
                    if($payment_detail->payment_type == "DEPOSIT"){
                        $customer = $payment->customer;
                        $customer->total_security_deposit = $customer->total_security_deposit - $payment_detail->amount;
                        $customer->save();
                    }
                }
                if($payment->total_not_allocate > 0){
                    $deposit = new Deposit;
                    $deposit->status_id = $payment->status_id;
                    $deposit->location_id = $payment->location_id;
                    $deposit->customer_id = $payment->customer_id;
                    $deposit->code = HomeController::getTransactionCode('deposits', 'DEP', $payment->location_id);
                    $deposit->category = "customer_credit";
                    $deposit->type_security_deposit = "IN";
                    $deposit->total_deposit = $payment->total_not_allocate;
                    $deposit->due_date = date("Y-m-d");
                    $deposit->payment_status = "PA";
                    if($deposit->save()){
                        $payment->deposit_id = $deposit->id;
                        if($payment->save()){
                            $customer = $payment->customer;
                            $customer->total_security_deposit = $customer->total_security_deposit + $payment->total_not_allocate;
                            $customer->save();
                        }
                    }
                }
            break;

            case "void":
                foreach($payment->payment_allocation as $payment_allocation){
                    $payment_status = 'NP';

                    if($payment_allocation->invoice_id != null){
                        $invoice = $payment_allocation->invoice;
                        $invoice->total_paid = $invoice->total_paid - $payment_allocation->payment_allocation;

                        if($invoice->total_paid > 0 ){
                            $payment_status = 'HP';
                        }

                        $invoice->payment_status = $payment_status;

                        if($invoice->save()){
                            if($invoice->booking_id != null){
                                $booking = $invoice->booking;
                                $booking->payment_status = $payment_status;
                                // $booking->total_paid = $booking->total_paid - $payment_allocation->payment_allocation;
                                $booking->save();
                            }
                            if($invoice->order_id != null){
                                $order = $invoice->order;
                                $order->payment_status = $payment_status;
                                // $order->total_paid = $order->total_paid - $payment_allocation->payment_allocation;
                                $order->save();
                            }
                            foreach($invoice->invoice_detail as $invoice_detail){
                                if($invoice_detail->booking_detail_id != null){
                                    $booking = $invoice_detail->booking_detail->booking;
                                    $booking->payment_status = $payment_status;
                                    $booking->save();
                                }
                                if($invoice_detail->order_detail_id != null){
                                    $order = $invoice_detail->order_detail->order;
                                    $order->payment_status = $payment_status;
                                    $order->save();
                                }
                            }
                        }
                    }

                    if($payment_allocation->deposit_id != null){
                        $deposit = $payment_allocation->deposit;
                        $deposit->total_paid = $deposit->total_paid - $payment_allocation->payment_allocation;

                        if($deposit->total_paid > 0 ){
                            $payment_status = 'HP';
                        }

                        $deposit->payment_status = $payment_status;

                        if($deposit->save()){
                            $customer = $deposit->customer;
                            $customer->total_security_deposit = $customer->total_security_deposit - $payment_allocation->payment_allocation;
                            $customer->save();
                        }
                    }
                }
                foreach($payment->payment_detail as $payment_detail){
                    if($payment_detail->payment_type == "DEPOSIT"){
                        $customer = $payment->customer;
                        $customer->total_security_deposit = $customer->total_security_deposit + $payment_detail->amount;
                        $customer->save();
                    }
                }
                if($payment->total_not_allocate > 0){
                    $deposit = $payment->deposit;
                    $deposit->status_id = $payment->status_id;
                    if($deposit->save()){
                        $customer = $payment->customer;
                        $customer->total_security_deposit = $customer->total_security_deposit - $payment->total_not_allocate;
                        $customer->save();
                    }
                }
            break;
        }
    }

    public function datatables()
    {
        $payments = Payment::join('statuses','statuses.id','payments.status_id')
            ->join('customers','customers.id','payments.customer_id')
            ->select('payments.*', 'statuses.name as status_name', 'customers.name as customer_name')
            ->where('payments.status_id', \Request::get('status_id'))
            ->get();

        return DataTables::of($payments)
            ->editColumn('total_payment', function ($data) {
                return number_format($data->total_payment,0,',','.');
            })
            ->editColumn('invoice_list', function($data){
                $invoice_codes = array();
                $invoices = Invoice::join('payment_allocations','payment_allocations.invoice_id','invoices.id')
                    ->join('payments','payments.id','payment_allocations.payment_id')
                    ->where('payments.id', $data->id)
                    ->select('invoices.code as invoice_code')
                    ->get();
                foreach($invoices as $detail){
                    array_push($invoice_codes, $detail->invoice_code);
                }
                return $invoice_codes;
            })
            ->editColumn('deposit_list', function($data){
                $deposit_codes = array();
                $deposits = Deposit::join('payment_allocations','payment_allocations.deposit_id','deposits.id')
                    ->join('payments','payments.id','payment_allocations.payment_id')
                    ->where('payments.id', $data->id)
                    ->select('deposits.code as deposit_code')
                    ->get();
                foreach($deposits as $detail){
                    array_push($deposit_codes, $detail->deposit_code);
                }
                return $deposit_codes;
            })
            ->make(true);
    }

    public function print($id){
        $data['payment'] = Payment::findOrFail($id);
        return view('pages.transaction.payment.print', $data);
    }

    public function complete($id){
        DB::beginTransaction();

        $payment = Payment::findOrFail($id);
        $payment->status_id = 4;
        if($payment->save()){
            foreach($payment->payment_allocation as $payment_allocation){
                if($payment_allocation->invoice_id != null){
                    $invoice = $payment_allocation->invoice;
                    if($invoice->payment_status == "PA"){
                        $invoice->status_id = 4;
                        if(!$invoice->save()){
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in completing payment code = '.$payment_code);
                        }
                    }
                }

                if($payment_allocation->deposit_id != null){
                    $deposit = $payment_allocation->deposit;
                    if($deposit->payment_status == "PA"){
                        $deposit->status_id = 4;
                        if(!$deposit->save()){
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in completing payment code = '.$payment_code);
                        }
                    }
                }
            }

            if($payment->deposit_id != null){
                $deposit = $payment->deposit;
                $deposit->status_id = 4;
                if(!$deposit->save()){
                    DB::rollBack();
                    \Session::flash('error', 'You are failed in completing payment code = '.$payment->code);
                }
            }

            DB::commit();
            \Session::flash('success', 'You are success in completing payment code = '.$payment->code);
        }else{
            DB::rollBack();
            \Session::flash('error', 'You are failed in completing payment code = '.$payment->code);
        }
        return Redirect::to($this->url);
    }
}
