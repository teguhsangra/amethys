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

class ProformaController extends Controller
{
    private $url = 'proforma';
    private $form_id = 'proforma_form';
    private $table_name = 'proformas';
    private $prefix_name = 'PRO';
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
        return view('pages.transaction.proforma.index', $data);
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
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['company'] = Company::get();
        $data['bank_accounts'] = BankAccount::get();

        return view('pages.transaction.proforma.editor', $data);
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

            $proforma = new Proforma;
			$proforma->has_po = $request['has_po'];
            $proforma->has_deduction = $request['has_deduction'];
            $proforma->po_number = $request['po_number'];
            // $proforma->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
            $proforma->code = self::createCustomCode($this->prefix_name, $request['location_id'], $request['proforma_date']);
            $proforma->status_id = $status->id;
            $proforma->company_id = $request['company_id'];
            $proforma->bank_account_id = $request['bank_account_id'];
            $proforma->location_id = $request['location_id'];
            $proforma->customer_id = $request['customer_id'];
            $proforma->contact_id = $request['contact_id'];
            $proforma->booking_id = $request['booking_id'];
            $proforma->order_id = $request['order_id'];
            $proforma->inquiry_id = $request['inquiry_id'];
            $proforma->detail_status = $request['detail_status'];
            $proforma->custom_status = $request['custom_status'];
            
            
            if($request['has_deduction'] == 'Y'){
            	$proforma->deduction_price = $request['deduction_price'];
            }else{
            	$proforma->deduction_price = 0;	
            }

            if ($request['custom_status'] == "Y") {
                if (!empty($request['custom_price'])) {
                    $proforma->total_price = $request['custom_price'];
                } else {
                    $proforma->total_price = 0;
                }

                if (!empty($request['custom_service_charge'])) {
                    $proforma->total_service_charge = $request['custom_service_charge'];
                } else {
                    $proforma->total_service_charge = 0;
                }

                if (!empty($request['custom_tax_price'])) {
                    $proforma->total_tax_price = $request['custom_tax_price'];
                } else {
                    $proforma->total_tax_price = 0;
                }
            } else {
                $proforma->total_price = $request['total_price'];
                $proforma->total_service_charge = $request['total_service_charge'];
                $proforma->total_tax_price = $request['total_tax_price'];
            }

            if(empty($request['round_price'])){
                $request['round_price'] = 0;
            }

            $proforma->stamp_duty = $request['stamp_duty'];
            $proforma->round_price = $request['round_price'];
            $proforma->total_deposit = $request['total_deposit'];
            $proforma->proforma_date = date("Y-m-d", strtotime($request['proforma_date']));
            $proforma->due_date = date("Y-m-d", strtotime($request['due_date']));
            $proforma->desc = $request['desc'];

            switch ($status->action) {
                case "draft":
                    $proforma->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $proforma->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $proforma->complete_by = Auth::user()->name;
                    break;
            }
			$proforma->total_price_on_tax = $request['total_price_on_tax'];
            if ($proforma->save()) {
                if ($request['custom_status'] == "N") {
                    for ($i = 0; $i < sizeof($request['detail_price']); $i++) {
                        $proforma_detail = new ProformaDetail;
                        $proforma_detail->proforma_id = $proforma->id;
                        $proforma_detail->booking_detail_id = $request['booking_detail_id'][$i];
                        $proforma_detail->order_detail_id = $request['order_detail_id'][$i];
                        $proforma_detail->booking_cancellation_id = $request['booking_cancellation_id'][$i];
                        $proforma_detail->name = $request['name'][$i];
                        $proforma_detail->detail_price = $request['detail_price'][$i];
                        $proforma_detail->detail_service_charge = $request['detail_service_charge'][$i];
                        $proforma_detail->detail_tax_price = $request['detail_tax_price'][$i];
                        $proforma_detail->remarks = $request['detail_remarks'][$i];
                        if (!$proforma_detail->save()) {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    }
                }

                if (!empty($request['deposit_id'])) {
                    for ($i = 0; $i < sizeof($request['deposit_id']); $i++) {
                        $proforma
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
        $data['proforma'] = Proforma::findOrFail($id);

        return view('pages.transaction.proforma.detail', $data);
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
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['bank_accounts'] = BankAccount::get();
        $data['company'] = Company::get();
        $data['proforma'] = Proforma::findOrFail($id);

        return view('pages.transaction.proforma.editor', $data);
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

            $proforma = Proforma::findOrFail($id);
            $proforma->has_po = $request['has_po'];
            $proforma->has_deduction = $request['has_deduction'];
            $proforma->po_number = $request['po_number'];
            $proforma->status_id = $status->id;
            $proforma->company_id = $request['company_id'];
            $proforma->bank_account_id = $request['bank_account_id'];
            $proforma->location_id = $request['location_id'];
            $proforma->customer_id = $request['customer_id'];
            $proforma->contact_id = $request['contact_id'];
            $proforma->booking_id = $request['booking_id'];
            $proforma->order_id = $request['order_id'];
            $proforma->inquiry_id = $request['inquiry_id'];
            $proforma->detail_status = $request['detail_status'];
            $proforma->custom_status = $request['custom_status'];
            
            if($request['has_deduction'] == 'Y'){
            	$proforma->deduction_price = $request['deduction_price'];
            }else{
            	$proforma->deduction_price = 0;	
            }

            if ($request['custom_status'] == "Y") {
                if (!empty($request['custom_price'])) {
                    $proforma->total_price = $request['custom_price'];
                } else {
                    $proforma->total_price = 0;
                }

                if (!empty($request['custom_service_charge'])) {
                    $proforma->total_service_charge = $request['custom_service_charge'];
                } else {
                    $proforma->total_service_charge = 0;
                }

                if (!empty($request['custom_tax_price'])) {
                    $proforma->total_tax_price = $request['custom_tax_price'];
                } else {
                    $proforma->total_tax_price = 0;
                }
            } else {
                $proforma->total_price = $request['total_price'];
                $proforma->total_service_charge = $request['total_service_charge'];
                $proforma->total_tax_price = $request['total_tax_price'];
            }

            if(empty($request['round_price'])){
                $request['round_price'] = 0;
            }
            $proforma->stamp_duty = $request['stamp_duty'];
            $proforma->round_price = $request['round_price'];
            $proforma->total_deposit = $request['total_deposit'];
            $proforma->proforma_date = date("Y-m-d", strtotime($request['proforma_date']));
            $proforma->due_date = date("Y-m-d", strtotime($request['due_date']));
            $proforma->desc = $request['desc'];

            switch ($status->action) {
                case "draft":
                    $proforma->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $proforma->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $proforma->complete_by = Auth::user()->name;
                    break;
            }
			$proforma->total_price_on_tax = $request['total_price_on_tax'];
            if ($proforma->save()) {
                if ($request['custom_status'] == "N") {
                    DB::table('proforma_details')->where('proforma_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['detail_price']); $i++) {
                        $proforma_detail = new ProformaDetail;
                        $proforma_detail->proforma_id = $proforma->id;
                        $proforma_detail->booking_detail_id = $request['booking_detail_id'][$i];
                        $proforma_detail->order_detail_id = $request['order_detail_id'][$i];
                        $proforma_detail->booking_cancellation_id = $request['booking_cancellation_id'][$i];
                        $proforma_detail->name = $request['name'][$i];
                        $proforma_detail->detail_price = $request['detail_price'][$i];
                        $proforma_detail->detail_service_charge = $request['detail_service_charge'][$i];
                        $proforma_detail->detail_tax_price = $request['detail_tax_price'][$i];
                        $proforma_detail->remarks = $request['detail_remarks'][$i];
                        if (!$proforma_detail->save()) {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in updating your data !!!');
                        }
                    }
                }

                DB::table('proforma_and_deposit')->where('proforma_id', $id)->delete();
                if (!empty($request['deposit_id'])) {
                    for ($i = 0; $i < sizeof($request['deposit_id']); $i++) {
                        $proforma
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
        $proforma = Proforma::findOrFail($id);

        $status = $proforma->status;
        if ($proforma->status->name == 'posted') {
            $status = Status::where('name', 'void')->first();
        } else if ($proforma->status->name == 'open') {
            $status = Status::where('name', 'discard')->first();
        }

        $active_status_id = array(1, 2, 4);
        if (sizeof($proforma->invoice->whereIn('status_id', $active_status_id)) > 0) {
            \Session::flash('error', 'Proforma = ' . $proforma->code . " can't be " . $status->name . ' because already used in other active transaction');
            return Redirect::to($this->url);
        }
        $proforma->status_id = $status->id;
        $proforma->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch ($status->action) {
            case "discard":
                $proforma->discard_by = Auth::user()->name;
                break;
            case "cancel":
                $proforma->cancel_by = Auth::user()->name;
                break;
        }

        if ($proforma->save()) {
            \Session::flash('success', 'Proforma = ' . $proforma->code . ' is ' . $status->name);
        } else {
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function get_child_of_this_employee($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);

        $show_data_by_structure = false;

        if ($a_g_and_module != null) {
            if ($a_g_and_module->showDataByStructure == 1) {
                $show_data_by_structure = true;
            }
        }

        if ($show_data_by_structure) {
            $employee = Employee::findOrFail($id);
            if (sizeof($employee->this_child) > 0) {
                foreach ($employee->this_child as $no => $detail) {
                    $this->ids[sizeof($this->ids)] = $detail->id;
                    $this->get_child_of_this_employee($detail->id);
                }
            }
        } else {
            $employees = Employee::where('id', '!=', $id)->get();
            foreach ($employees as $detail) {
                array_push($this->ids, $detail->id);
            }
        }
    }

    public function getProformaData(Request $request)
    {
        $location_id = $request['location_id'];
        $customer_id = $request['customer_id'];
        $id = $request['id'];
        $active_status_id = array(1, 2, 4);
        $booking_detail_ids = array();
        $order_detail_ids = array();
        $booking_cancellation_ids = array();

        if ($id != null || $id != '') {
            $proforma_booking_detail_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.booking_detail_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->where('proformas.id', '!=', $id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();

            $proforma_order_detail_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.order_detail_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->where('proformas.id', '!=', $id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();

            $proforma_booking_cancellation_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.booking_cancellation_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->where('proformas.id', '!=', $id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();
        } else {
            $proforma_booking_detail_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.booking_detail_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();

            $proforma_order_detail_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.order_detail_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();

            $proforma_booking_cancellation_id = ProformaDetail::join('proformas', 'proformas.id', 'proforma_details.proforma_id')
                ->select('proforma_details.booking_cancellation_id')
                ->where('proformas.location_id', $location_id)
                ->where('proformas.customer_id', $customer_id)
                ->whereIn('proformas.status_id', $active_status_id)
                ->get();
        }

        $invoice_booking_detail_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
            ->select('invoice_details.booking_detail_id')
            ->where('invoices.location_id', $location_id)
            ->where('invoices.customer_id', $customer_id)
            ->whereIn('invoices.status_id', $active_status_id)
            ->get();

        foreach ($proforma_booking_detail_id as $no => $detail) {
            if ($detail->booking_detail_id != null)
                array_push($booking_detail_ids, $detail->booking_detail_id);
        }
        foreach ($invoice_booking_detail_id as $no => $detail) {
            if ($detail->booking_detail_id != null)
                array_push($booking_detail_ids, $detail->booking_detail_id);
        }

        $invoice_order_detail_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
            ->select('invoice_details.order_detail_id')
            ->where('invoices.location_id', $location_id)
            ->where('invoices.customer_id', $customer_id)
            ->whereIn('invoices.status_id', $active_status_id)
            ->get();
        foreach ($proforma_order_detail_id as $no => $detail) {
            if ($detail->order_detail_id != null)
                array_push($order_detail_ids, $detail->order_detail_id);
        }
        foreach ($invoice_order_detail_id as $no => $detail) {
            if ($detail->order_detail_id != null)
                array_push($order_detail_ids, $detail->order_detail_id);
        }

        $invoice_booking_cancellation_id = InvoiceDetail::join('invoices', 'invoices.id', 'invoice_details.invoice_id')
            ->select('invoice_details.booking_cancellation_id')
            ->where('invoices.location_id', $location_id)
            ->where('invoices.customer_id', $customer_id)
            ->whereIn('invoices.status_id', $active_status_id)
            ->get();
        foreach ($proforma_booking_cancellation_id as $no => $detail) {
            if ($detail->booking_cancellation_id != null)
                array_push($booking_cancellation_ids, $detail->booking_cancellation_id);
        }
        foreach ($invoice_booking_cancellation_id as $no => $detail) {
            if ($detail->booking_cancellation_id != null)
                array_push($booking_cancellation_ids, $detail->booking_cancellation_id);
        }

        $return['serviced_offices'] = BookingDetail::join('bookings', 'bookings.id', 'booking_details.booking_id')
            ->join('rooms', 'rooms.id', 'booking_details.room_id')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->join('room_categories', 'r_c_and_room.room_category_id', 'room_categories.id')
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type')
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
            ->select('booking_details.*', 'bookings.code', 'products.name as product_name', 'packages.name as package_name', 'bookings.type')
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
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type')
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
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type')
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
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type')
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
            ->select('booking_details.*', 'bookings.code', 'rooms.room_number', 'bookings.type')
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
        $return['inquiries'] = Inquiry::select('inquiries.*', 'customers.name as customer_name')
            ->join('customers', 'customers.id', 'inquiries.customer_id')
            ->where('customer_id', $customer_id)
            ->where('location_id', $location_id)
            ->where('status_id', 2)
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

        return $return;
    }

    public function datatables()
    {
        $proformas = Proforma::join('customers', 'customers.id', 'proformas.customer_id')
            ->join('statuses', 'statuses.id', 'proformas.status_id')
            ->join('locations', 'locations.id', 'proformas.location_id')
            ->select('proformas.*', 'statuses.name as status_name', 'customers.name as customer_name', 'locations.name as location_name')
            ->where('proformas.status_id', \Request::get('status_id'))
            ->get();

        return DataTables::of($proformas)
            ->editColumn('total_proforma', function ($data) {
            	return number_format($data->total_price + $data->total_service_charge + $data->total_tax_price + $data->stamp_duty + $data->total_deposit + $data->round_price, 0, ',', '.'); 
            })
            ->make(true);
    }

    public function print($id)
    {
        $data['company_name'] = $this->company_name;
        $data['proforma'] = Proforma::findOrFail($id);

        return view('pages.transaction.proforma.print', $data);
    }

    public static function createCustomCode($prefix_name, $location_id, $proforma_date)
    {
        $year = date("Y", strtotime($proforma_date));
        $month = date("m", strtotime($proforma_date));

        $location = Location::findOrFail($location_id);

        $sequence = DB::table('proformas')
            ->where('location_id', $location_id)
            ->count();

        $sequence++;

        $check_unique_code = false;

        while (!$check_unique_code){
            $code = $location->code . '-' . HomeController::setZero($sequence);

            $get_detail_data = DB::table('proformas')->where('code', $code)->first();

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
