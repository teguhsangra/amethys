<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Transaction\InvoiceController;
use App\Models\ParameterSetting;
use App\Models\Status;
use App\Models\Company;
use App\Models\Notification;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Employee;
use App\Models\Product;
use App\Models\BankAccount;
use App\Models\NonCash;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\PaymentAllocation;
use App\Models\Location;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class OrderController extends Controller
{
    private $url = 'point_of_sales';
    private $form_id = 'point_of_sales_form';
    private $table_name = 'orders';
    private $prefix_name = 'POS';
    private $ids = array();
    private $tax_percentage = 0;
    private $service_charge = 0;
    private $total_mod_rounding = 0;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $parameter_of_tax_percentage = ParameterSetting::where('name', 'tax_percentage')->first();
        $this->tax_percentage = $parameter_of_tax_percentage->double_value;
        $parameter_of_service_charge = ParameterSetting::where('name', 'service_charge')->first();
        $this->service_charge = $parameter_of_service_charge->double_value;
        $parameter_of_total_mod_rounding = ParameterSetting::where('name', 'total_mod_rounding')->first();
        $this->total_mod_rounding = $parameter_of_total_mod_rounding->int_value;
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
        $data['employee'] = $employee;
        $data['statuses'] = Status::all();
        $location_id = \Request::get('location_id');

        $data['location_id'] = $location_id;
        $data['location'] = Location::all();
        return view('pages.transaction.point_of_sales.index', $data);
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
        $data['products'] = Product::where('main_status', 'N')->get();
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        return view('pages.transaction.point_of_sales.editor', $data);
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
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $order = new Order;
            $order->status_id = $status->id;
            $order->location_id = $request['location_id'];
            $order->customer_id = $request['customer_id'];
            $order->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
            $order->order_date = date('Y-m-d');
            $order->total_price = $request['total_price'];
            $order->total_service_charge = $request['total_service_charge'];
            $order->total_tax_price = $request['total_tax_price'];
            $order->usable_discount = $request['usable_discount'];
            $order->discount_percentage = $request['discount_percentage'];
            $order->discount_price = $request['discount_price'];
            $order->remarks = $request['remarks'];
            $order->tax_status = $request['tax_status'];
            $order->round_price = $request['round_price'];

            switch ($status->action) {
                case "draft":
                    $order->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $order->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $order->complete_by = Auth::user()->name;
                    break;
            }

            if ($order->save()) {
                $total_discount_price = $request['discount_price'];
                if ($total_discount_price > 0) {
                    $single_discount_price = ceil($total_discount_price / sizeof($request['other_product_id']));
                }

                for ($i = 0; $i < sizeof($request['other_product_id']); $i++) {
                    $product = Product::findOrFail($request['other_product_id'][$i]);
                    $detail_price = $request['ac_detail_price'][$i];
                    $detail_discount_price = 0;

                    if ($order->usable_discount == "percentage") {
                        $detail_discount_price = $detail_price * ($order->discount_percentage / 100);
                    } else if ($order->usable_discount == "price") {
                        if ($total_discount_price > 0) {
                            $temp = $total_discount_price - $single_discount_price;
                            if ($temp < 0) {
                                $detail_discount_price = $total_discount_price;
                            } else {
                                $detail_discount_price = $single_discount_price;
                            }
                            $total_discount_price = $total_discount_price - $detail_discount_price;
                        }
                    }
                    $detail_price = $detail_price - $detail_discount_price;
                    if ($product->has_service_charge == "Y") {
                        $detail_service_charge = $detail_price * $this->service_charge;
                    } else {
                        $detail_service_charge = 0;
                    }

                    if ($order->tax_status == null) {
                        $detail_tax_price  = 0;
                    } else {
                        if ($order->tax_status == 'no_tax') {
                            $detail_tax_price = 0;
                        } else if ($order->tax_status == 'exclude') {
                            $detail_tax_price = ($detail_price + $detail_service_charge) * $this->tax_percentage;
                        } else if ($order->tax_status == 'include') {
                            $temp = $detail_price;
                            $detail_price = round($temp / (1 + $this->tax_percentage));
                            $detail_tax_price = $temp - $detail_price;
                            $detail_service_charge = 0;
                            $has_service_charge = $product->has_service_charge;

                            if ($has_service_charge == "Y") {
                                $temp_1 = $detail_price;
                                $detail_price = round($temp_1 / (1 + $this->service_charge));
                                $detail_service_charge = $temp_1 - $detail_price;
                            }
                        } else {
                            $detail_tax_price = 0;
                        }
                    }
                    $order_detail = new OrderDetail;
                    $order_detail->order_id = $order->id;
                    $order_detail->start_date = $request['ac_start_date'][$i];
                    $order_detail->end_date = $request['ac_end_date'][$i];
                    $order_detail->length_of_term = $request['ac_length_of_term'][$i];
                    $order_detail->start_time = $request['ac_start_time'][$i];
                    $order_detail->end_time = $request['ac_end_time'][$i];
                    $order_detail->product_id = $request['other_product_id'][$i];
                    $order_detail->quantity = $request['ac_quantity'][$i];
                    $order_detail->detail_price = $detail_price;
                    $order_detail->detail_service_charge = $detail_service_charge;
                    $order_detail->detail_tax_price = $detail_tax_price;
                    $order_detail->usable_discount = $request['usable_discount'];
                    $order_detail->detail_discount_percentage = $request['discount_percentage'];
                    $order_detail->detail_discount_price = $detail_discount_price;
                    $order_detail->remarks = $request['ac_remarks'][$i];
                    if (!$order_detail->save()) {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }

                // Start : Logic for Payment On Point Of Sales
                if ($request['pay_now'] == 'Y') {
                    $last_invoice = Invoice::where('location_id', $request['location_id'])
                        ->orderBy('id', 'desc')
                        ->where('other_code', 'POS')
                        ->first();
                    if ($last_invoice == null) {
                        $last_number = 1;
                    } else {
                        $last_number = $last_invoice->last_number + 1;
                    }

                    $company = Company::orderBy('id', 'asc')->first();

                    $contact = Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
                        ->where('customer_and_contact.customer_id', $request['customer_id'])
                        ->first();

                    if ($contact == null) {
                        $contact = Contact::orderBy('id', 'asc')->first();
                    }

                    $invoice = new Invoice;
                    $invoice->status_id = 2;
                    $invoice->company_id = $company->id;
                    $invoice->location_id = $request['location_id'];
                    $invoice->customer_id = $request['customer_id'];
                    $invoice->contact_id = $contact->id;
                    $invoice->order_id = $order->id;
                    // $invoice->code = HomeController::getTransactionCode('invoices', 'INV', $request['location_id'], null, 'POS', $last_number);
                    $invoice->code = InvoiceController::createCustomCode('INV', $request['location_id'], date("Y-m-d"));
                    $invoice->last_number = $last_number;
                    $invoice->other_code = 'POS';
                    $invoice->detail_status = 'N';
                    $invoice->total_price = $order->total_price;
                    $invoice->total_service_charge = $order->total_service_charge;
                    $invoice->total_tax_price = $order->total_tax_price;
                    $invoice->total_paid = $order->total_price + $order->total_tax_price + $order->total_service_charge;
                    $invoice->invoice_date = date("Y-m-d");
                    $invoice->due_date = date("Y-m-d");
                    $invoice->payment_status = 'PA';
                    $invoice->desc = $order->remarks;
                    $invoice->posting_by = Auth::user()->name;
                    if ($invoice->save()) {
                        $payment = new Payment;
                        $payment->status_id = 2;
                        $payment->location_id = $request['location_id'];
                        $payment->customer_id = $request['customer_id'];
                        $payment->code = HomeController::getTransactionCode('payments', 'PAY', $request['location_id']);
                        $payment->total_payment = $invoice->total_paid;
                        $payment->payment_date = date('Y-m-d');
                        $payment->posting_by = Auth::user()->name;
                        if ($payment->save()) {
                            for ($i = 0; $i < sizeof($request['payment_amount']); $i++) {
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
                                if (!$payment_detail->save()) {
                                    DB::rollBack();
                                    \Session::flash('error', 'You are failed in inputing your data !!!');
                                }
                            }

                            $payment_allocation = new PaymentAllocation;
                            $payment_allocation->payment_id = $payment->id;
                            $payment_allocation->invoice_id = $invoice->id;
                            $payment_allocation->total_need = $invoice->total_paid;
                            $payment_allocation->payment_allocation = $invoice->total_paid;
                            if (!$payment_allocation->save()) {
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            }
                            $order->status_id = 2;
                            $order->posting_by = Auth::user()->name;
                            $order->payment_status = 'PA';
                            if ($order->save()) {
                                foreach ($order->order_detail as $order_detail) {
                                    $order_detail->payment_status = 'PA';
                                    $order_detail->save();
                                }
                            } else {
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            }
                        } else {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Logic for Payment On Point Of Sales

                self::sendNotification($order);

                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            } else {
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
        $data['print_url'] = $this->url . '/print/' . $id;
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;

        $data['order'] = Order::findOrFail($id);
        $invoice = Invoice::where('order_id', $data['order']->id)->first();
        $payment = null;
        $invoice_url = null;
        $payment_url = null;

        if ($invoice != null) {
            foreach ($invoice->payment_allocation as $payment_allocation) {
                $payment = $payment_allocation->payment;
                break;
            }
            $invoice_url = '/invoice/print/' . $invoice->id;
            $payment_url = '/payment/print/' . $payment->id;
        }

        $data['invoice_url'] = $invoice_url;
        $data['payment_url'] = $payment_url;

        $data['invoice'] = $invoice;
        $data['payment'] = $payment;

        return view('pages.transaction.point_of_sales.detail', $data);
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

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['non_cashes'] = NonCash::get();
        $data['bank_accounts'] = BankAccount::get();
        $data['products'] = Product::where('main_status', 'N')->get();
        $data['order'] = Order::findOrFail($id);
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        return view('pages.transaction.point_of_sales.editor', $data);
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
        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
            'customer_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $order = Order::findOrFail($id);
            $order->status_id = $status->id;
            $order->location_id = $request['location_id'];
            $order->customer_id = $request['customer_id'];
            $order->order_date = date('Y-m-d');
            $order->total_price = $request['total_price'];
            $order->total_service_charge = $request['total_service_charge'];
            $order->total_tax_price = $request['total_tax_price'];
            $order->usable_discount = $request['usable_discount'];
            $order->discount_percentage = $request['discount_percentage'];
            $order->discount_price = $request['discount_price'];
            $order->remarks = $request['remarks'];
            $order->tax_status = $request['tax_status'];

            switch ($status->action) {
                case "draft":
                    $order->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $order->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $order->complete_by = Auth::user()->name;
                    break;
            }

            if ($order->save()) {
                OrderDetail::where('order_id', $id)->delete();

                $total_discount_price = $request['discount_price'];
                if ($total_discount_price > 0) {
                    $single_discount_price = ceil($total_discount_price / sizeof($request['other_product_id']));
                }

                for ($i = 0; $i < sizeof($request['other_product_id']); $i++) {
                    $product = Product::findOrFail($request['other_product_id'][$i]);
                    $detail_price = $request['ac_detail_price'][$i];
                    $detail_discount_price = 0;

                    if ($order->usable_discount == "percentage") {
                        $detail_discount_price = $detail_price * ($order->discount_percentage / 100);
                    } else if ($order->usable_discount == "price") {
                        if ($total_discount_price > 0) {
                            $temp = $total_discount_price - $single_discount_price;
                            if ($temp < 0) {
                                $detail_discount_price = $total_discount_price;
                            } else {
                                $detail_discount_price = $single_discount_price;
                            }
                            $total_discount_price = $total_discount_price - $detail_discount_price;
                        }
                    }
                    $detail_price = $detail_price - $detail_discount_price;
                    if ($product->has_service_charge == "Y") {
                        $detail_service_charge = $detail_price * $this->service_charge;
                    } else {
                        $detail_service_charge = 0;
                    }

                    if ($order->tax_status == null) {
                        $detail_tax_price  = 0;
                    } else {
                        if ($order->tax_status == 'no_tax') {
                            $detail_tax_price = 0;
                        } else if ($order->tax_status == 'exclude') {
                            $detail_tax_price = ($detail_price + $detail_service_charge) * $this->tax_percentage;
                        } else if ($order->tax_status == 'include') {
                            $temp = $detail_price;
                            $detail_price = round($temp / (1 + $this->tax_percentage));
                            $detail_tax_price = $temp - $detail_price;
                            $detail_service_charge = 0;
                            $has_service_charge = $product->has_service_charge;

                            if ($has_service_charge == "Y") {
                                $temp_1 = $detail_price;
                                $detail_price = round($temp_1 / (1 + $this->service_charge));
                                $detail_service_charge = $temp_1 - $detail_price;
                            }
                        } else {
                            $detail_tax_price = 0;
                        }
                    }
                    $order_detail = new OrderDetail;
                    $order_detail->order_id = $order->id;
                    $order_detail->start_date = $request['ac_start_date'][$i];
                    $order_detail->end_date = $request['ac_end_date'][$i];
                    $order_detail->length_of_term = $request['ac_length_of_term'][$i];
                    $order_detail->start_time = $request['ac_start_time'][$i];
                    $order_detail->end_time = $request['ac_end_time'][$i];
                    $order_detail->product_id = $request['other_product_id'][$i];
                    $order_detail->quantity = $request['ac_quantity'][$i];
                    $order_detail->detail_price = $detail_price;
                    $order_detail->detail_service_charge = $detail_service_charge;
                    $order_detail->detail_tax_price = $detail_tax_price;
                    $order_detail->usable_discount = $request['usable_discount'];
                    $order_detail->detail_discount_percentage = $request['discount_percentage'];
                    $order_detail->detail_discount_price = $detail_discount_price;
                    $order_detail->remarks = $request['ac_remarks'][$i];
                    if (!$order_detail->save()) {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }

                // Start : Logic for Payment On Point Of Sales
                if ($request['pay_now'] == 'Y') {
                    $last_invoice = Invoice::where('location_id', $request['location_id'])
                        ->orderBy('id', 'desc')
                        ->where('other_code', 'POS')
                        ->first();
                    if ($last_invoice == null) {
                        $last_number = 1;
                    } else {
                        $last_number = $last_invoice->last_number + 1;
                    }


                    $contact = Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
                        ->where('customer_and_contact.customer_id', $request['customer_id'])
                        ->first();

                    if ($contact == null) {
                        $contact = Contact::orderBy('id', 'asc')->first();
                    }

                    $invoice = new Invoice;
                    $invoice->status_id = 2;
                    $invoice->location_id = $request['location_id'];
                    $invoice->customer_id = $request['customer_id'];
                    $invoice->contact_id = $contact->id;
                    $invoice->order_id = $order->id;
                    $invoice->code = HomeController::getTransactionCode('invoices', 'INV', $request['location_id'], null, 'POS', $last_number);
                    $invoice->last_number = $last_number;
                    $invoice->other_code = 'POS';
                    $invoice->detail_status = 'N';
                    $invoice->total_price = $order->total_price;
                    $invoice->total_service_charge = $order->total_service_charge;
                    $invoice->total_tax_price = $order->total_tax_price;
                    $invoice->total_paid = $order->total_price + $order->total_tax_price + $order->total_service_charge;
                    $invoice->invoice_date = date("Y-m-d");
                    $invoice->due_date = date("Y-m-d");
                    $invoice->payment_status = 'PA';
                    $invoice->desc = $order->remarks;
                    $invoice->posting_by = Auth::user()->name;
                    if ($invoice->save()) {
                        $payment = new Payment;
                        $payment->status_id = 2;
                        $payment->location_id = $request['location_id'];
                        $payment->customer_id = $request['customer_id'];
                        $payment->code = HomeController::getTransactionCode('payments', 'PAY', $request['location_id']);
                        $payment->total_payment = $invoice->total_paid;
                        $payment->payment_date = date('Y-m-d');
                        $payment->posting_by = Auth::user()->name;
                        if ($payment->save()) {
                            for ($i = 0; $i < sizeof($request['payment_amount']); $i++) {
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
                                if (!$payment_detail->save()) {
                                    DB::rollBack();
                                    \Session::flash('error', 'You are failed in inputing your data !!!');
                                }
                            }

                            $payment_allocation = new PaymentAllocation;
                            $payment_allocation->payment_id = $payment->id;
                            $payment_allocation->invoice_id = $invoice->id;
                            $payment_allocation->total_need = $invoice->total_paid;
                            $payment_allocation->payment_allocation = $invoice->total_paid;
                            if (!$payment_allocation->save()) {
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            }
                            $order->status_id = 2;
                            $order->posting_by = Auth::user()->name;
                            $order->payment_status = 'PA';
                            if ($order->save()) {
                                foreach ($order->order_detail as $order_detail) {
                                    $order_detail->payment_status = 'PA';
                                    $order_detail->save();
                                }
                            } else {
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            }
                        } else {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Logic for Payment On Point Of Sales

                self::sendNotification($order);

                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            } else {
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $discard_or_cancel_reason = \Request::get('discard_or_cancel_reason');
        $order = Order::findOrFail($id);

        $status = $order->status;
        if ($order->status->name == 'posted') {
            $status = Status::where('name', 'void')->first();
        } else if ($order->status->name == 'open') {
            $status = Status::where('name', 'discard')->first();
        }

        $active_status_id = array(1, 2, 4);
        $order_detail_ids = array();

        foreach($order->order_detail as $order_detail){
            array_push($order_detail_ids, $order_detail->id);
        }

        $total_proforma = DB::table('proformas')
            ->whereIn('status_id', $active_status_id)
            ->where('order_id', $id)
            ->count();

        $total_proforma_with_detail = DB::table('proforma_details')
            ->join('proformas', 'proformas.id', 'proforma_details.proforma_id')
            ->whereIn('proformas.status_id', $active_status_id)
            ->whereIn('proforma_details.order_detail_id', $order_detail_ids)
            ->count();

        $total_invoice = DB::table('invoices')
            ->whereIn('status_id', $active_status_id)
            ->where('order_id', $id)
            ->count();

        $total_invoice_with_detail = DB::table('invoice_details')
            ->join('invoices', 'invoices.id', 'invoice_details.invoice_id')
            ->whereIn('invoices.status_id', $active_status_id)
            ->whereIn('invoice_details.order_detail_id', $order_detail_ids)
            ->count();

        $total_transaction = $total_proforma + $total_proforma_with_detail + $total_invoice + $total_invoice_with_detail;

        if ($total_transaction > 0) {
            \Session::flash('error', 'Order = ' . $order->code . " can't be " . $status->name . ' because already used in other active transaction');
            return Redirect::to($this->url);
        }
        $order->status_id = $status->id;
        $order->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch ($status->action) {
            case "discard":
                $order->discard_by = Auth::user()->name;
                break;
            case "cancel":
                $order->cancel_by = Auth::user()->name;
                break;
        }

        if ($order->save()) {
            \Session::flash('success', 'Order = ' . $order->code . ' is ' . $status->name);
        } else {
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function print($id)
    {
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['order'] = Order::findOrFail($id);
        return view('pages.transaction.point_of_sales.print', $data);
    }

    public function get_by_id($order_id)
    {
        return Order::findOrFail($order_id);
    }


    public function get_by_customer_id($customer_id)
    {
        return Order::where('customer_id', $customer_id)->get();
    }

    public function datatables()
    {
        $location_id = \Request::get('location_id');

        if ($location_id != null) {
            $orders = Order::join('statuses', 'statuses.id', 'orders.status_id')
                ->join('customers', 'customers.id', 'orders.customer_id')
                ->select('orders.*', 'statuses.name as status_name', 'customers.name as customer_name')
                ->where('orders.status_id', \Request::get('status_id'))
                ->where('orders.location_id', $location_id)
                ->get();
        } else {
            $orders = Order::join('statuses', 'statuses.id', 'orders.status_id')
                ->join('customers', 'customers.id', 'orders.customer_id')
                ->select('orders.*', 'statuses.name as status_name', 'customers.name as customer_name')
                ->where('orders.status_id', \Request::get('status_id'))
                ->get();
        }


        return DataTables::of($orders)
            ->editColumn('total_price', function ($data) {
                return number_format($data->total_price + $data->total_tax_price + $data->total_service_charge, 0, ',', '.');
            })
            ->editColumn('product_list', function ($data) {
                $products = array();
                $rooms = Product::join('order_details', 'order_details.product_id', 'products.id')
                    ->where('order_details.order_id', $data->id)
                    ->select('products.name')
                    ->distinct()
                    ->get();
                foreach ($rooms as $detail) {
                    array_push($products, $detail->name);
                }
                return $products;
            })
            ->make(true);
    }

    public static function sendNotification($order)
    {
        $return = true;

        $array_access_group_id = array();
        $url = 'point_of_sales';

        $a_g_and_module = DB::table('a_g_and_module')
            ->join('modules', 'modules.id', 'a_g_and_module.module_id')
            ->where('a_g_and_module.isExec', 1)
            ->where('modules.link', $url)
            ->get();

        foreach ($a_g_and_module as $detail) {
            array_push($array_access_group_id, $detail->access_group_id);
        }

        if ($order->status->name == "open") {
            $employees = Employee::join('users', 'users.id', 'employees.user_id')
                ->whereIn('users.access_group_id', $array_access_group_id)
                ->get();

            foreach ($employees as $no => $detail) {
                $notification = new Notification;
                $notification->user_id = $detail->user->id;
                $notification->header = "To Do : Approve POS " . $order->code . " by " . $order->draft_by;
                $notification->body = "To Do : Approve POS " . $order->code . " by " . $order->draft_by;
                $notification->url = $url . '/' . $order->id . '/edit';
    
                if (!$notification->save()) {
                    $return = false;
                    break;
                }
            }
        } else {
            Notification::where('url', $url . '/' . $order->id . '/edit')->update(['read_status' => 'Y']);
        }

        return $return;
    }
}
