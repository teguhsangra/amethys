<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Status;
use App\Models\ParameterSetting;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Company;
use App\Models\BankAccount;
use App\Models\Inquiry;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\BookingCancellation;
use App\Models\Proforma;
use App\Models\ProformaDetail;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class InvoiceController extends Controller
{private $url = 'invoice';
    private $form_id = 'invoice_form';
    private $table_name = 'invoices';
    private $prefix_name = 'INV';
    private $total_mod_rounding = 0;
    private $company_name = '';
    private $ids = array();
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $parameter_of_total_mod_rounding = ParameterSetting::where('name', 'total_mod_rounding')->first();
        $this->total_mod_rounding = $parameter_of_total_mod_rounding->int_value;
        $parameter_of_company_name = ParameterSetting::where('name', 'company_name')->first();
        $this->company_name = $parameter_of_company_name->string_value;
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
        return view('pages.transaction.invoice.index', $data);
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
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['company'] = Company::get();
        $data['bank_accounts'] = BankAccount::get();
        $data['proformas'] = Proforma::where('inquiry_id', null)->whereIn('status_id', $active_status_id)->get();

        if (!empty(\Request::get('proforma_id'))) {
            $data['proforma'] = Proforma::findOrFail(\Request::get('proforma_id'));
        }

        return view('pages.transaction.invoice.editor', $data);
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
            'bank_account_id' => 'required',
            'company_id' => 'required',
            'customer_id' => 'required',
            // 'contact_id' => 'required',
            'location_id' => 'required',
            'detail_status' => 'required',
            'custom_status' => 'required',
            'due_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $last_invoice = Invoice::where('location_id', $request['location_id'])
                ->orderBy('id', 'desc')
                ->first();
            if ($last_invoice == null) {
                $last_number = 1;
            } else {
                $last_number = $last_invoice->last_number + 1;
            }

            $invoice = new Invoice;
            $invoice->has_po = $request['has_po'];
            $invoice->has_deduction = $request['has_deduction'];
            $invoice->po_number = $request['po_number'];
            // $invoice->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
            $invoice->code = $this->createCustomCode($this->prefix_name, $request['location_id'], $request['invoice_date']);
            $invoice->status_id = $status->id;
            $invoice->proforma_id = $request['proforma_id'];
            $invoice->company_id = $request['company_id'];
            $invoice->bank_account_id = $request['bank_account_id'];
            $invoice->location_id = $request['location_id'];
            $invoice->customer_id = $request['customer_id'];
            $invoice->contact_id = $request['contact_id'];
            $invoice->booking_id = $request['booking_id'];
            $invoice->order_id = $request['order_id'];
            $invoice->last_number = $last_number;
            $invoice->detail_status = $request['detail_status'];
            $invoice->custom_status = $request['custom_status'];
            $invoice->address_status = $request['address_status'];
            
            if($request['has_deduction'] == 'Y'){
            	$invoice->deduction_price = $request['deduction_price'];
            }else{
            	$invoice->deduction_price = 0;	
            }

            if ($request['custom_status'] == "Y") {
                if (!empty($request['custom_price'])) {
                    $invoice->total_price = $request['custom_price'];
                } else {
                    $invoice->total_price = 0;
                }

                if (!empty($request['custom_service_charge'])) {
                    $invoice->total_service_charge = $request['custom_service_charge'];
                } else {
                    $invoice->total_service_charge = 0;
                }

                if (!empty($request['custom_tax_price'])) {
                    $invoice->total_tax_price = $request['custom_tax_price'];
                } else {
                    $invoice->total_tax_price = 0;
                }
            } else {
                $invoice->booking_id = null;
                $invoice->order_id = null;
                $invoice->total_price = $request['total_price'];
                $invoice->total_service_charge = $request['total_service_charge'];
                $invoice->total_tax_price = $request['total_tax_price'];
                $invoice->stamp_duty = $request['stamp_duty'];
                $invoice->round_price = $request['round_price'];
                $invoice->total_deposit = $request['total_deposit'];
            }
			
            $invoice->invoice_date = date("Y-m-d", strtotime($request['invoice_date']));
            $invoice->due_date = date("Y-m-d", strtotime($request['due_date']));
            $invoice->desc = $request['desc'];

            switch ($status->action) {
                case "draft":
                    $invoice->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $invoice->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $invoice->complete_by = Auth::user()->name;
                    break;
            }
			$invoice->total_price_on_tax = $request['total_price_on_tax'];
            if ($invoice->save()) {
                if ($request['custom_status'] == "N") {
                    for ($i = 0; $i < sizeof($request['detail_price']); $i++) {
                        $invoice_detail = new InvoiceDetail;
                        $invoice_detail->invoice_id = $invoice->id;
                        $invoice_detail->booking_detail_id = $request['booking_detail_id'][$i];
                        $invoice_detail->order_detail_id = $request['order_detail_id'][$i];
                        $invoice_detail->booking_cancellation_id = $request['booking_cancellation_id'][$i];
                        $invoice_detail->name = $request['name'][$i];
                        $invoice_detail->detail_price = $request['detail_price'][$i];
                        $invoice_detail->detail_service_charge = $request['detail_service_charge'][$i];
                        $invoice_detail->detail_tax_price = $request['detail_tax_price'][$i];
                        $invoice_detail->remarks = $request['detail_remarks'][$i];
                        if (!$invoice_detail->save()) {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    }
                }
                if ($request['proforma_id'] != '') {
                    $proforma = Proforma::findOrFail($request['proforma_id']);
                    $proforma->status_id = 4;
                    if (!$proforma->save()) {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }

                if (!empty($request['deposit_id'])) {
                    for ($i = 0; $i < sizeof($request['deposit_id']); $i++) {
                        $invoice
                            ->deposits()
                            ->attach($request['deposit_id'][$i]);
                    }
                }
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            } else {
                DB::rollBack();
                \Session::flash('error', 'You are failed in inputing your data !!!');
            }

            self::execute_invoice($invoice->id);

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
        $data['company_name'] = $this->company_name;
        $data['invoice'] = Invoice::findOrFail($id);
        $data['bank_accounts'] = BankAccount::get();
        return view('pages.transaction.invoice.detail', $data);
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
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';

        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['bank_accounts'] = BankAccount::get();
        $data['proformas'] = Proforma::where('inquiry_id', null)->whereIn('status_id', $active_status_id)->get();
        $data['company'] = Company::get();

        $data['invoice'] = Invoice::findOrFail($id);

        return view('pages.transaction.invoice.editor', $data);
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
            'bank_account_id' => 'required',
            'company_id' => 'required',
            'customer_id' => 'required',
            // 'contact_id' => 'required',
            'location_id' => 'required',
            'detail_status' => 'required',
            'custom_status' => 'required',
            'due_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $last_invoice = Invoice::where('location_id', $request['location_id'])
                ->orderBy('id', 'desc')
                ->first();
            if ($last_invoice == null) {
                $last_number = 1;
            } else {
                $last_number = $last_invoice->last_number + 1;
            }

            $invoice = Invoice::findOrFail($id);
            $invoice->has_po = $request['has_po'];
            $invoice->has_deduction = $request['has_deduction'];
            $invoice->po_number = $request['po_number'];
            $invoice->status_id = $status->id;
            $invoice->proforma_id = $request['proforma_id'];
            $invoice->bank_account_id = $request['bank_account_id'];
            $invoice->company_id = $request['company_id'];
            $invoice->location_id = $request['location_id'];
            $invoice->customer_id = $request['customer_id'];
            $invoice->contact_id = $request['contact_id'];
            $invoice->booking_id = $request['booking_id'];
            $invoice->order_id = $request['order_id'];
            $invoice->detail_status = $request['detail_status'];
            $invoice->custom_status = $request['custom_status'];
            $invoice->address_status = $request['address_status'];
            
            if($request['has_deduction'] == 'Y'){
            	$invoice->deduction_price = $request['deduction_price'];
            }else{
            	$invoice->deduction_price = 0;	
            }

            if ($request['custom_status'] == "Y") {
                if (!empty($request['custom_price'])) {
                    $invoice->total_price = $request['custom_price'];
                } else {
                    $invoice->total_price = 0;
                }

                if (!empty($request['custom_service_charge'])) {
                    $invoice->total_service_charge = $request['custom_service_charge'];
                } else {
                    $invoice->total_service_charge = 0;
                }

                if (!empty($request['custom_tax_price'])) {
                    $invoice->total_tax_price = $request['custom_tax_price'];
                } else {
                    $invoice->total_tax_price = 0;
                }
            } else {
                $invoice->booking_id = null;
                $invoice->order_id = null;
                $invoice->total_price = $request['total_price'];
                $invoice->total_service_charge = $request['total_service_charge'];
                $invoice->total_tax_price = $request['total_tax_price'];
                $invoice->stamp_duty = $request['stamp_duty'];
                $invoice->round_price = $request['round_price'];
                $invoice->total_deposit = $request['total_deposit'];
            }
			
            $invoice->invoice_date = date("Y-m-d", strtotime($request['invoice_date']));
            $invoice->due_date = date("Y-m-d", strtotime($request['due_date']));
            $invoice->desc = $request['desc'];

            switch ($status->action) {
                case "draft":
                    $invoice->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $invoice->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $invoice->complete_by = Auth::user()->name;
                    break;
            }
			$invoice->total_price_on_tax = $request['total_price_on_tax'];
            if ($invoice->save()) {
                if ($request['custom_status'] == "N") {
                    DB::table('invoice_details')->where('invoice_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['detail_price']); $i++) {
                        $invoice_detail = new InvoiceDetail;
                        $invoice_detail->invoice_id = $invoice->id;
                        $invoice_detail->booking_detail_id = $request['booking_detail_id'][$i];
                        $invoice_detail->order_detail_id = $request['order_detail_id'][$i];
                        $invoice_detail->booking_cancellation_id = $request['booking_cancellation_id'][$i];
                        $invoice_detail->name = $request['name'][$i];
                        $invoice_detail->detail_price = $request['detail_price'][$i];
                        $invoice_detail->detail_service_charge = $request['detail_service_charge'][$i];
                        $invoice_detail->detail_tax_price = $request['detail_tax_price'][$i];
                        $invoice_detail->remarks = $request['detail_remarks'][$i];
                        if (!$invoice_detail->save()) {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    }
                }
                if ($request['proforma_id'] != '') {
                    $proforma = Proforma::findOrFail($request['proforma_id']);
                    $proforma->status_id = 4;
                    if (!$proforma->save()) {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in updating your data !!!');
                    }
                }

                DB::table('invoice_and_deposit')->where('invoice_id', $id)->delete();
                if (!empty($request['deposit_id'])) {
                    for ($i = 0; $i < sizeof($request['deposit_id']); $i++) {
                        $invoice
                            ->deposits()
                            ->attach($request['deposit_id'][$i]);
                    }
                }
                DB::commit();
                \Session::flash('success', 'You are success in updating your data');
            } else {
                DB::rollBack();
                \Session::flash('error', 'You are failed in updating your data !!!');
            }

            self::execute_invoice($invoice->id);

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
        $invoice = Invoice::findOrFail($id);

        $status = $invoice->status;
        if ($invoice->status->name == 'posted') {
            $status = Status::where('name', 'void')->first();
        } else if ($invoice->status->name == 'open') {
            $status = Status::where('name', 'discard')->first();
        }

        $active_status_id = array(1, 2, 4);
        if (sizeof($invoice->payment_allocation) > 0) {
            foreach ($invoice->payment_allocation as $payment_allocation) {
                $payment = $payment_allocation->payment;
                if ($payment->status_id == 1 || $payment->status_id == 2 || $payment->status_id == 4) {
                    \Session::flash('error', 'Invoice = ' . $invoice->code . " can't be " . $status->name . ' because already used in other active transaction');
                    return Redirect::to($this->url);
                }
            }
        }
        $invoice->status_id = $status->id;
        $invoice->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch ($status->action) {
            case "discard":
                $invoice->discard_by = Auth::user()->name;
                break;
            case "cancel":
                $invoice->cancel_by = Auth::user()->name;
                break;
        }

        if ($invoice->save()) {
            \Session::flash('success', 'Invoice = ' . $invoice->code . ' is ' . $status->name);
        } else {
            \Session::flash('error', 'Failed');
        }

        self::execute_invoice($invoice->id);

        return Redirect::to($this->url);
    }

    public function get_by_param($location_id, $customer_id, $payment_status)
    {
        $active_status_id = array(1, 2);

        return Invoice::where('location_id', $location_id)
            ->where('customer_id', $customer_id)
            ->where('payment_status', '!=', $payment_status)
            ->whereIn('status_id', $active_status_id)
            ->get();
    }

    public function getInvoiceData(Request $request)
    {
        $location_id = $request['location_id'];
        $customer_id = $request['customer_id'];
        $id = $request['id'];
        $proforma_id = $request['proforma_id'];
        $active_status_id = array(1, 2, 4);
        $booking_detail_ids = array();
        $order_detail_ids = array();
        $booking_cancellation_ids = array();

        if ($id != null || $id != '') {
            $invoice = Invoice::findOrFail($id);
            $proforma_id = $invoice->proforma_id;

            $invoice_booking_detail_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
                ->select('invoice_details.booking_detail_id')
                ->where('invoices.location_id', $location_id)
                ->where('invoices.customer_id', $customer_id)
                ->where('invoices.id', '!=', $id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();

            $invoice_order_detail_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
                ->select('invoice_details.order_detail_id')
                ->where('invoices.location_id', $location_id)
                ->where('invoices.customer_id', $customer_id)
                ->where('invoices.id', '!=', $id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();

            $invoice_booking_cancellation_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
                ->select('invoice_details.booking_cancellation_id')
                ->where('invoices.location_id', $location_id)
                ->where('invoices.customer_id', $customer_id)
                ->where('invoices.id', '!=', $id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
        } else {
            $invoice_booking_detail_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
                ->select('invoice_details.booking_detail_id')
                ->where('invoices.location_id', $location_id)
                ->where('invoices.customer_id', $customer_id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
            $invoice_order_detail_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
                ->select('invoice_details.order_detail_id')
                ->where('invoices.location_id', $location_id)
                ->where('invoices.customer_id', $customer_id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
            $invoice_booking_cancellation_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
                ->select('invoice_details.booking_cancellation_id')
                ->where('invoices.location_id', $location_id)
                ->where('invoices.customer_id', $customer_id)
                ->whereIn('invoices.status_id', $active_status_id)
                ->get();
        }

        // Start : Get booking detail not in proforma
        if ($proforma_id != '' || $proforma_id != null) {
            $invoice_booking_detail_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.booking_detail_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->where('proformas.id', '!=', $proforma_id)
                ->get();
        } else {
            $invoice_booking_detail_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.booking_detail_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();
        }

        foreach ($invoice_booking_detail_id as $no => $detail) {
            if ($detail->booking_detail_id != null)
                array_push($booking_detail_ids, $detail->booking_detail_id);
        }
        foreach ($invoice_booking_detail_id as $no => $detail) {
            if ($detail->booking_detail_id != null)
                array_push($booking_detail_ids, $detail->booking_detail_id);
        }
        // End : Get booking detail not in proforma

        // Start : Get order detail not in proforma
        if ($proforma_id != '' || $proforma_id != null) {
            $invoice_order_detail_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.order_detail_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->where('proformas.id', '!=', $proforma_id)
                ->get();
        } else {
            $invoice_order_detail_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.order_detail_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();
        }

        foreach ($invoice_order_detail_id as $no => $detail) {
            if ($detail->order_detail_id != null)
                array_push($order_detail_ids, $detail->order_detail_id);
        }
        foreach ($invoice_order_detail_id as $no => $detail) {
            if ($detail->order_detail_id != null)
                array_push($order_detail_ids, $detail->order_detail_id);
        }
        // End : Get order detail not in proforma

        // Start : Get booking cancellation not in proforma
        if ($proforma_id != '' || $proforma_id != null) {
            $invoice_booking_cancellation_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.booking_cancellation_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->where('proformas.id', '!=', $proforma_id)
                ->get();
        } else {
            $invoice_booking_cancellation_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.booking_cancellation_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();
        }

        foreach ($invoice_booking_cancellation_id as $no => $detail) {
            if ($detail->booking_cancellation_id != null)
                array_push($booking_cancellation_ids, $detail->booking_cancellation_id);
        }
        foreach ($invoice_booking_cancellation_id as $no => $detail) {
            if ($detail->booking_cancellation_id != null)
                array_push($booking_cancellation_ids, $detail->booking_cancellation_id);
        }
        // End : Get booking cancellation not in proforma

        $return['serviced_offices'] = BookingDetail::join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('rooms', 'rooms.id', 'booking_details.room_id')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'r_c_and_room.room_category_id', 'room_categories.id')
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type', 'bookings.term_of_payment')
            ->where('bookings.location_id', $location_id)
            ->where('bookings.customer_id', $customer_id)
            ->where('bookings.status_id', 2)
            ->where('booking_details.payment_status', '!=', 'PA')
            ->where('room_categories.code', 'SO')
            ->whereNotIn('booking_details.id', $booking_detail_ids)
            ->get();

        $return['virtual_office'] = BookingDetail::join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->leftJoin('products', 'products.id', 'booking_details.product_id')
            ->leftJoin('packages', 'packages.id', 'booking_details.package_id')
            ->leftJoin('rooms', 'rooms.id', 'booking_details.room_id')
            ->select('booking_details.*', 'bookings.code', 'products.name as product_name', 'packages.name as package_name', 'bookings.type', 'bookings.term_of_payment')
            ->where('bookings.location_id', $location_id)
            ->where('bookings.customer_id', $customer_id)
            ->where('bookings.is_main_agreement', 'Y')
            ->where('bookings.product_id', '!=', null)
            ->where('bookings.type', '=', 'product')
            ->where('products.main_status', 'Y')
            ->where('bookings.status_id', 2)
            ->where('booking_details.payment_status', '!=', 'PA')
            ->whereNotIn('booking_details.id', $booking_detail_ids)
            ->get();

        $return['meeting_rooms'] = BookingDetail::join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('rooms', 'rooms.id', 'booking_details.room_id')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'r_c_and_room.room_category_id', 'room_categories.id')
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type', 'bookings.term_of_payment')
            ->where('bookings.location_id', $location_id)
            ->where('bookings.customer_id', $customer_id)
            ->where('bookings.status_id', 2)
            ->where('booking_details.payment_status', '!=', 'PA')
            ->where('room_categories.code', 'MR')
            ->whereNotIn('booking_details.id', $booking_detail_ids)
            ->get();

        $return['coworking'] = BookingDetail::join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('rooms', 'rooms.id', 'booking_details.room_id')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'r_c_and_room.room_category_id', 'room_categories.id')
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type', 'bookings.term_of_payment')
            ->where('bookings.location_id', $location_id)
            ->where('bookings.customer_id', $customer_id)
            ->where('bookings.status_id', 2)
            ->where('booking_details.payment_status', '!=', 'PA')
            ->where('room_categories.code', 'CW')
            ->whereNotIn('booking_details.id', $booking_detail_ids)
            ->get();

        $return['hotel'] = BookingDetail::join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('rooms', 'rooms.id', 'booking_details.room_id')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'r_c_and_room.room_category_id', 'room_categories.id')
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type', 'bookings.term_of_payment')
            ->where('bookings.location_id', $location_id)
            ->where('bookings.customer_id', $customer_id)
            ->where('bookings.status_id', 2)
            ->where('booking_details.payment_status', '!=', 'PA')
            ->where('room_categories.code', 'LO')
            ->whereNotIn('booking_details.id', $booking_detail_ids)
            ->get();

        $return['regular_offices'] = BookingDetail::join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('rooms', 'rooms.id', 'booking_details.room_id')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'r_c_and_room.room_category_id', 'room_categories.id')
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type', 'bookings.term_of_payment')
            ->where('bookings.location_id', $location_id)
            ->where('bookings.customer_id', $customer_id)
            ->where('bookings.status_id', 2)
            ->where('booking_details.payment_status', '!=', 'PA')
            ->where('room_categories.code', 'RO')
            ->whereNotIn('booking_details.id', $booking_detail_ids)
            ->get();

        $return['order_details'] = OrderDetail::join('orders', 'orders.id', 'order_details.order_id')
            ->join('products', 'products.id', 'order_details.product_id')
            ->select('order_details.*', 'orders.code', 'orders.order_date', 'products.name as product_name')
            ->where('orders.location_id', $location_id)
            ->where('orders.customer_id', $customer_id)
            ->where('orders.status_id', 2)
            ->where('order_details.payment_status', '!=', 'PA')
            ->whereNotIn('order_details.id', $order_detail_ids)
            ->get();

        $return['booking_cancellations'] = BookingCancellation::join('bookings', 'bookings.id', 'booking_cancellations.booking_id')
            ->leftJoin('products', 'products.id', 'bookings.product_id')
            ->leftJoin('packages', 'packages.id', 'bookings.package_id')
            ->leftJoin('rooms', 'rooms.id', 'bookings.room_id')
            ->select('booking_cancellations.*', 'bookings.code', 'bookings.start_date', 'products.name as product_name', 'packages.name as package_name', 'rooms.room_number')
            ->where('bookings.location_id', $location_id)
            ->where('bookings.customer_id', $customer_id)
            ->where('booking_cancellations.payment_status', '!=', 'PA')
            ->whereNotIn('booking_cancellations.id', $booking_cancellation_ids)
            ->get();
        $return['bookings'] = Booking::select('bookings.*', 'customers.name as customer_name')
            ->join('customers', 'customers.id', 'bookings.customer_id')
            ->where('customer_id', $customer_id)
            ->where('location_id', $location_id)
            ->where('payment_status', '!=', 'PA')
            ->where('status_id', 2)
            ->get();
        $return['orders'] = Order::where('customer_id', $customer_id)
            ->where('location_id', $location_id)
            ->where('payment_status', '!=', 'PA')
            ->where('status_id', 2)
            ->get();

        $return['package'] = BookingDetail::join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('packages', 'packages.id', 'booking_details.package_id')
            ->select('booking_details.*', 'bookings.code', 'packages.name as package_name')
            ->where('bookings.location_id', $location_id)
            ->where('bookings.customer_id', $customer_id)
            ->where('bookings.type', 'package')
            ->where('bookings.status_id', 2)
            ->where('booking_details.payment_status', '!=', 'PA')
            ->whereNotIn('booking_details.id', $booking_detail_ids)
            ->get();

        return $return;
    }


    public static function execute_invoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $total_invoice = $invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price;

        switch ($invoice->status->name) {
            case "posted":
                if ($invoice->custom_status == "Y") {
                    if ($invoice->booking_id != null) {
                        $booking = $invoice->booking;
                        $booking->total_paid = $booking->total_paid + $total_invoice;

                        if($booking->total_paid < $total_invoice){
                            $booking->payment_status = "HP";
                        }else{
                            $booking->payment_status = "PA";
                        }

                        $booking->save();

                        BookingDetail::where('booking_id', $invoice->booking_id)
                            ->update(['payment_status' => 'PA']);
                    } else if ($invoice->order_id != null) {
                        $order = $invoice->order;
                        $order->total_paid = $order->total_paid + $total_invoice;

                        if($order->total_paid < $total_invoice){
                            $order->payment_status = "HP";
                        }else{
                            $order->payment_status = "PA";
                        }
                        $order->save();

                        OrderDetail::where('order_id', $invoice->order_id)
                            ->update(['payment_status' => 'PA']);
                    }
                } else {
                    foreach ($invoice->invoice_detail as $invoice_detail) {
                        if ($invoice_detail->booking_detail_id != null) {
                            $booking_detail = $invoice_detail->booking_detail;
                            $booking_detail->payment_status = 'PA';
                            $booking_detail->save();
                        } else if ($invoice_detail->order_detail_id != null) {
                            $order_detail = $invoice_detail->order_detail;
                            $order_detail->payment_status = 'PA';
                            $order_detail->save();
                        } else if ($invoice_detail->booking_cancellation_id != null) {
                            $booking_cancellation = $invoice_detail->booking_cancellation;
                            $booking_cancellation->payment_status = 'PA';
                            $booking_cancellation->save();
                        }
                    }
                }
                break;

            case "void":
                if ($invoice->custom_status == "Y") {
                    if ($invoice->booking_id != null) {
                        $booking = $invoice->booking;
                        $booking->total_paid = $booking->total_paid - $total_invoice;

                        if($booking->total_paid > 0){
                            $booking->payment_status = "HP";
                        }else{
                            $booking->payment_status = "NP";
                        }

                        $booking->save();

                        BookingDetail::where('booking_id', $invoice->booking_id)
                            ->update(['payment_status' => 'NP']);
                    } else if ($invoice->order_id != null) {
                        $order = $invoice->order;
                        $order->total_paid = $order->total_paid - $total_invoice;

                        if($order->total_paid > 0){
                            $order->payment_status = "HP";
                        }else{
                            $order->payment_status = "NP";
                        }

                        $order->save();

                        OrderDetail::where('order_id', $invoice->order_id)
                            ->update(['payment_status' => 'NP']);
                    }
                } else {
                    foreach ($invoice->invoice_detail as $invoice_detail) {
                        if ($invoice_detail->booking_detail_id != null) {
                            $booking_detail = $invoice_detail->booking_detail;
                            $booking_detail->payment_status = 'NP';
                            $booking_detail->save();
                        } else if ($invoice_detail->order_detail_id != null) {
                            $order_detail = $invoice_detail->order_detail;
                            $order_detail->payment_status = 'NP';
                            $order_detail->save();
                        } else if ($invoice_detail->booking_cancellation_id != null) {
                            $booking_cancellation = $invoice_detail->booking_cancellation;
                            $booking_cancellation->payment_status = 'NP';
                            $booking_cancellation->save();
                        }
                    }
                    InvoiceDetail::where('invoice_id', $id)->delete();
                }
                break;
        }
    }

    public function datatables()
    {
        $invoices = Invoice::join('customers', 'customers.id', 'invoices.customer_id')
            ->join('statuses', 'statuses.id', 'invoices.status_id')
            ->join('locations', 'locations.id', 'invoices.location_id')
            ->select('invoices.*', 'statuses.name as status_name', 'customers.name as customer_name', 'locations.name as location_name')
            ->where('invoices.status_id', \Request::get('status_id'))
            ->get();

        return DataTables::of($invoices)
            ->editColumn('total_invoice', function ($data) {
                return number_format($data->total_price + $data->total_service_charge + $data->total_tax_price + $data->stamp_duty + $data->round_price, 0, ',', '.');
            })
            ->editColumn('total_outstanding', function ($data) {
                return number_format($data->total_price + $data->total_service_charge + $data->total_tax_price + $data->stamp_duty + $data->round_price - $data->total_paid, 0, ',', '.');
            })
            ->make(true);
    }

    public function print($id)
    {
        $data['company_name'] = $this->company_name;
        $data['invoice'] = Invoice::findOrFail($id);
        $data['bank_accounts'] = BankAccount::get();
        return view('pages.transaction.invoice.print', $data);
    }

    public static function createCustomCode($prefix_name, $location_id, $invoice_date)
    {
        $year = date("Y", strtotime($invoice_date));
        $month = date("m", strtotime($invoice_date));

        $location = Location::findOrFail($location_id);
	
	
		$last_invoice = Invoice::where('location_id', $location_id)
            ->orderBy('id', 'desc')
            ->first();
                
        if ($last_invoice == null) {
        	
            $sequence = DB::table('invoices')
            ->where('location_id', $location_id)
            ->count();
            
            $sequence++;
        } else {
            $sequence = $last_invoice->last_number + 1;
        }
            
       
       

        $check_unique_code = false;

        while (!$check_unique_code) {
            $code = $location->code . '-' . HomeController::setZero($sequence);

            $get_detail_data = DB::table('invoices')->where('code', $code)->first();

            if ($get_detail_data == null) {
                $check_unique_code = true;
            } else {
                $sequence++;
                $check_unique_code = false;
            }
        }

        return $code;
    }
}
