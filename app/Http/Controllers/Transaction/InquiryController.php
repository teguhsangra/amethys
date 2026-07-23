<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Transaction\SalesActivityController;
use App\Models\ParameterSetting;
use App\Models\Company;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Referral;
use App\Models\Agent;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\NatureOfBusiness;
use App\Models\RoomCategory;
use App\Models\Inquiry;
use App\Models\Notification;
use App\Models\Proforma;
use App\Models\ProformaDetail;
use App\Models\BankAccount;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class InquiryController extends Controller
{
    private $url = 'inquiry';
    private $form_id = 'inquiry_form';
    private $table_name = 'inquiries';
    private $prefix_name = 'INQ';
    private $ids = array();
    private $parent_ids = array();
    private $office_hour_start = 0;
    private $office_hour_end = 0;
    private $after_office_hour_end = 0;
    private $total_mod_rounding = 0;
    private $tax_percentage = 0;
    private $service_charge = 0;
    private $default_currency = '';
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
        $parameter_of_office_hour_start = ParameterSetting::where('name', 'office_hour_start')->first();
        $this->office_hour_start = $parameter_of_office_hour_start->int_value;
        $parameter_of_office_hour_end = ParameterSetting::where('name', 'office_hour_end')->first();
        $this->office_hour_end = $parameter_of_office_hour_end->int_value;
        $parameter_of_after_office_hour_end = ParameterSetting::where('name', 'after_office_hour_end')->first();
        $this->after_office_hour_end = $parameter_of_after_office_hour_end->int_value;
        $parameter_of_total_mod_rounding = ParameterSetting::where('name', 'total_mod_rounding')->first();
        $this->total_mod_rounding = $parameter_of_total_mod_rounding->int_value;
        $company_name = ParameterSetting::where('name', 'company_name')->first();
        $this->company_name = $company_name->string_value;
        $company_address_1 = ParameterSetting::where('name', 'company_address_1')->first();
        $this->company_address_1 = $company_address_1->string_value;
        $company_address_2 = ParameterSetting::where('name', 'company_address_2')->first();
        $this->company_address_2 = $company_address_2->string_value;
        $company_phone = ParameterSetting::where('name', 'company_phone')->first();
        $this->company_phone = $company_phone->string_value;
        $company_fax = ParameterSetting::where('name', 'company_fax')->first();
        $this->company_fax = $company_fax->string_value;
        $default_currency = ParameterSetting::where('name', 'default_currency')->first();
        $this->default_currency = $default_currency->string_value;
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
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        $data['bank_accounts'] = BankAccount::get();
        $data['companies'] = Company::get();
        return view('pages.transaction.inquiry.index', $data);
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

        if (!empty(\Request::get('inquiry_id'))) {
            $inquiry = Inquiry::findOrFail(\Request::get('inquiry_id'));
            if ($inquiry->status_id == 3 || $inquiry->status_id == 4 || $inquiry->status_id == 5) {
                \Session::flash('warning', 'Sorry, you can not use inquiry no = ' . $inquiry->code . ', because this inquiry status = ' . $inquiry->status->name . ' !!!');
                return Redirect::to($this->url);
            }
            $data['inquiry'] = $inquiry;
        }

        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['office_hour_start'] = $this->office_hour_start;
        $data['office_hour_end'] = $this->office_hour_end;
        $data['after_office_hour_end'] = $this->after_office_hour_end;
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['default_currency'] = $this->default_currency;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';

        $data['locations'] = Auth::user()->location;
        $data['prospects'] = Prospect::whereIn('status_id', $active_status_id)->whereIn('employee_id', $this->ids)->get();
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['referrals'] = Referral::get();
        $data['products'] = Product::where('main_status', 'Y')->get();
        $data['other_products'] = Product::where('main_status', 'N')->get();
        $data['agents'] = Agent::get();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        $data['room_categories'] = RoomCategory::get();

        return view('pages.transaction.inquiry.editor', $data);
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
            'employee_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $inquiry_id = null;
            $prospect_id = null;
            $referral_id = null;
            $agent_id = null;
            $customer_id = null;
            $contact_id = null;
            $room_id = null;
            $room_type_id = null;
            $room_category_id = null;
            $product_id = null;
            $package_id = null;
            $customer_status = 'E';

            // Start :Logic for customer
            if ($request['customer_status'] == 'N') {
                $customer = new Customer;
                $customer->code = HomeController::getMasterCode('customers', 'Cust');
                $customer->nature_of_business_id = $request['nature_of_business_id'];
                $customer->name = $request['customer_name'];
                $customer->customer_type = $request['customer_type'];
                $customer->email = $request['customer_email'];
                $customer->phone = $request['customer_phone'];
                if ($customer->save()) {
                    $customer_id = $customer->id;
                } else {
                    \Session::flash('error', 'You are failed in inputing your data !!!');
                    DB::rollBack();
                    return Redirect::to($this->url);
                }
            } else {
                $customer_id = $request['customer_id'];
            }

            $referral_id = $request['referral_id'];
            $agent_id = $request['agent_id'];
            $customer_status = $request['customer_status'];
            // End :Logic for customer

            // Start : Logic for using prospect
            if ($request['inquiry_id'] != null) {
                $inquiry = Inquiry::findOrFail($request['inquiry_id']);
                $inquiry_id = $inquiry->id;
                $prospect_id = $inquiry->prospect_id;
            } else if ($request['prospect_id'] != null) {
                $prospect = Prospect::findOrFail($request['prospect_id']);
                $prospect_id = $prospect->id;
                $customer_id = $prospect->customer_id;
                $contact_id = $prospect->contact_id;
                $referral_id = $prospect->referral_id;
                $agent_id = $prospect->agent_id;
                $customer_status = $prospect->customer_status;
            } else {
                // Do Nothing
            }

            // Start :Logic for contact
            if ($request['contact_status'] == "same_with_customer") {
                $contact = Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
                    ->where('customer_and_contact.customer_id', $customer_id)
                    ->where('customer_and_contact.default_status', 'Y')
                    ->first();
                if ($contact != null) {
                    $contact_id = $contact->id;
                } else {
                    $customer = Customer::findOrFail($customer_id);

                    $contact = new Contact;
                    $contact->code = HomeController::getMasterCode('contacts', 'Co');
                    $contact->name = $customer->name;
                    $contact->email = $customer->email;
                    $contact->phone = $customer->phone;
                    if ($contact->save()) {
                        $contact_id = $contact->id;
                        $customer->contact()->attach($contact_id, ['default_status' => 'Y']);
                    } else {
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                        DB::rollBack();
                        return Redirect::to($this->url);
                    }
                }
            } else {
                if ($request['contact_new_status'] == 'N') {
                    $contact = new Contact;
                    $contact->code = HomeController::getMasterCode('contacts', 'Co');
                    $contact->name = $request['contact_name'];
                    $contact->email = $request['contact_email'];
                    $contact->phone = $request['contact_phone'];
                    if ($contact->save()) {
                        $contact_id = $contact->id;
                        $customer = Customer::findOrFail($customer_id);
                        $customer->contact()->attach($contact_id, ['default_status' => 'Y', 'position' => $request['contact_positon'], 'department' => $request['contact_department']]);
                    } else {
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                        DB::rollBack();
                        return Redirect::to($this->url);
                    }
                } else {
                    if (!empty($request['contact_id'])) {
                        $contact_id = $request['contact_id'];
                    }
                }
            }
            // End :Logic for contact
            // End : Logic for using prospect

            // Start : Logic for type (package, product, room)
            if (!empty($request['product_id'])) {
                $product_id = $request['product_id'];
            }
            $room_category_id = $request['room_category_id'];
            // End : Logic for type (package, product, room)

            $status = Status::where('name', $request['status_name'])->first();

            $inquiry = new Inquiry;
            $inquiry->status_id = $status->id;
            $inquiry->location_id = $request['location_id'];
            $inquiry->employee_id = $request['employee_id'];
            $inquiry->prospect_id = $prospect_id;
            $inquiry->referral_id = $referral_id;
            $inquiry->agent_id = $agent_id;
            $inquiry->customer_id = $customer_id;
            $inquiry->contact_id = $contact_id;
            $inquiry->room_id = $room_id;
            $inquiry->room_type_id = $room_type_id;
            $inquiry->room_category_id = $room_category_id;
            $inquiry->product_id = $product_id;
            $inquiry->inquiry_id = $inquiry_id;
            $inquiry->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
            $inquiry->type = $request['type'];
            $inquiry->price_type = $request['price_type'];
            $inquiry->customer_status = $customer_status;
            $inquiry->start_time = $request['start_time'];
            $inquiry->end_time = $request['end_time'];
            $inquiry->start_date = date('Y-m-d', strtotime($request['start_date']));
            $inquiry->end_date = date('Y-m-d', strtotime($request['end_date']));
            $inquiry->length_of_term = $request['length_of_term'];
            $inquiry->remarks = $request['remarks'];
            $inquiry->detail_price = $request['detail_price'];
            $inquiry->usable_discount = $request['usable_discount'];
            $inquiry->discount_percentage = $request['discount_percentage'];
            $inquiry->discount_price = $request['discount_price'];
            $inquiry->tax_status = $request['tax_status'];
            $inquiry->start_date_counted = $request['start_date_counted'];
            $inquiry->total_price = $request['total_price'];
            $inquiry->total_tax_price = $request['total_tax_price'];
            $inquiry->total_service_charge = $request['total_service_charge'];
            $inquiry->total_additional_charge = $request['total_additional_charge'];
            $inquiry->total_service_charge_additional_charge = $request['total_service_charge_additional_charge'];
            $inquiry->total_tax_additional_charge = $request['total_tax_additional_charge'];
            $inquiry->security_deposit = $request['security_deposit'];
            $inquiry->quantity = $request['quantity'];
            $inquiry->term_of_payment = $request['term_of_payment'];
            $inquiry->free_term_booking = $request['free_term_booking'];
            $inquiry->term_notice_period = $request['term_notice_period'];

            switch ($status->action) {
                case "draft":
                    $inquiry->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $inquiry->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $inquiry->complete_by = Auth::user()->name;
                    break;
            }

            if ($inquiry->save()) {
                if ($prospect_id != null) {
                    $prospect = Prospect::findOrFail($prospect_id);
                    $prospect->status_id = 4;
                    $prospect->save();
                }
                if ($inquiry_id != null) {
                    $other_inquiry = Inquiry::findOrFail($inquiry_id);
                    $other_inquiry->status_id = 4;
                    $other_inquiry->save();
                }

                if (!empty($request['package_id'])) {
                    for ($i = 0; $i < sizeof($request['package_id']); $i++) {
                        $inquiry
                            ->packages()
                            ->attach($request['package_id'][$i], [
                                'price_type' => $request['package_price_type'][$i],
                                'detail_price' => $request['package_detail_price'][$i],
                                'quantity' => $request['package_quantity'][$i],
                                'start_date' => date('Y-m-d', strtotime($request['package_start_date'][$i])),
                                'end_date' => date('Y-m-d', strtotime($request['package_end_date'][$i])),
                                'start_time' => $request['package_start_time'][$i],
                                'end_time' => $request['package_end_time'][$i],
                                'length_of_term' => $request['package_length_of_term'][$i]
                            ]);
                    }
                }

                if (!empty($request['room_id'])) {
                    for ($i = 0; $i < sizeof($request['room_id']); $i++) {
                        $inquiry->rooms()->attach($request['room_id'][$i], ['detail_price' => $request['room_detail_price'][$i]]);
                    }
                }

                if (!empty($request['other_product_id'])) {
                    for ($i = 0; $i < sizeof($request['other_product_id']); $i++) {
                        $inquiry->products()->attach($request['other_product_id'][$i], [
                            'detail_price' => $request['ac_detail_price'][$i],
                            'quantity' => $request['ac_quantity'][$i],
                            'start_date' => date('Y-m-d', strtotime($request['ac_start_date'][$i])),
                            'end_date' => date('Y-m-d', strtotime($request['ac_end_date'][$i])),
                            'start_time' => $request['ac_start_time'][$i],
                            'end_time' => $request['ac_end_time'][$i],
                            'length_of_term' => $request['ac_length_of_term'][$i]
                        ]);
                    }
                }

                $this->sendApprovalNotification($inquiry->employee_id, $inquiry->id);

                if ($status->action == "posting") {
                    if (SalesActivityController::create_by_inquiry($inquiry)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
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
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['print_url'] = $this->url . '/print/' . $id;
        $data['agreement_url'] = $this->url . '/agreement/' . $id;
        $data['inquiry'] = Inquiry::findOrFail($id);
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['default_currency'] = $this->default_currency;
        $data['company_name'] = $this->company_name;
        $data['company_address_1'] = $this->company_address_1;
        $data['company_address_2'] = $this->company_address_2;
        $data['company_phone'] = $this->company_phone;
        $data['company_fax'] = $this->company_fax;
        return view('pages.transaction.inquiry.detail', $data);
    }

    public function aggrement($id)
    {

        $data['inquiry'] = Inquiry::findOrFail($id);
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['default_currency'] = $this->default_currency;
        $data['company_name'] = $this->company_name;
        $data['company_address_1'] = $this->company_address_1;
        $data['company_address_2'] = $this->company_address_2;
        $data['company_phone'] = $this->company_phone;
        $data['company_fax'] = $this->company_fax;
        return view('pages.transaction.inquiry.agreement', $data);
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
        $data['office_hour_start'] = $this->office_hour_start;
        $data['office_hour_end'] = $this->office_hour_end;
        $data['after_office_hour_end'] = $this->after_office_hour_end;
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['default_currency'] = $this->default_currency;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';

        $data['locations'] = Auth::user()->location;
        $data['prospects'] = Prospect::whereIn('status_id', $active_status_id)->whereIn('employee_id', $this->ids)->get();
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['referrals'] = Referral::get();
        $data['products'] = Product::where('main_status', 'Y')->get();
        $data['other_products'] = Product::where('main_status', 'N')->get();
        $data['agents'] = Agent::get();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        $data['room_categories'] = RoomCategory::get();

        $data['inquiry'] = Inquiry::findOrFail($id);

        return view('pages.transaction.inquiry.editor', $data);
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
            'employee_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $inquiry = Inquiry::findOrFail($id);

            $room_id = null;
            $room_type_id = null;
            $product_id = null;
            $package_id = null;

            $contact_id = $inquiry->contact_id;
            $customer_id = $inquiry->customer_id;
            // Start :Logic for contact
            if ($request['contact_status'] == "same_with_customer") {
                $contact = Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
                    ->where('customer_and_contact.customer_id', $customer_id)
                    ->where('customer_and_contact.default_status', 'Y')
                    ->first();
                if ($contact != null) {
                    $contact_id = $contact->id;
                } else {
                    $customer = Customer::findOrFail($customer_id);

                    $contact = new Contact;
                    $contact->code = HomeController::getMasterCode('contacts', 'Co');
                    $contact->name = $customer->name;
                    $contact->email = $customer->email;
                    $contact->phone = $customer->phone;
                    if ($contact->save()) {
                        $contact_id = $contact->id;
                        $customer->contact()->attach($contact_id, ['default_status' => 'Y']);
                    } else {
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                        DB::rollBack();
                        return Redirect::to($this->url);
                    }
                }
            } else {
                if ($request['contact_new_status'] == 'N') {
                    $contact = new Contact;
                    $contact->code = HomeController::getMasterCode('contacts', 'Co');
                    $contact->name = $request['contact_name'];
                    $contact->email = $request['contact_email'];
                    $contact->phone = $request['contact_phone'];
                    if ($contact->save()) {
                        $contact_id = $contact->id;
                        $customer = Customer::findOrFail($customer_id);
                        $customer->contact()->attach($contact_id, ['default_status' => 'Y', 'position' => $request['contact_positon'], 'department' => $request['contact_department']]);
                    } else {
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                        DB::rollBack();
                        return Redirect::to($this->url);
                    }
                } else {
                    if (!empty($request['contact_id'])) {
                        $contact_id = $request['contact_id'];
                    }
                }
            }
            // End :Logic for contact

            // Start : Logic for type (package, product, room)
            if (!empty($request['product_id'])) {
                $product_id = $request['product_id'];
            }
            $room_category_id = $request['room_category_id'];
            // End : Logic for type (package, product, room)

            $status = Status::where('name', $request['status_name'])->first();

            $inquiry->status_id = $status->id;
            $inquiry->contact_id = $contact_id;
            $inquiry->room_id = $room_id;
            $inquiry->room_type_id = $room_type_id;
            $inquiry->room_category_id = $room_category_id;
            $inquiry->product_id = $product_id;
            $inquiry->type = $request['type'];
            $inquiry->price_type = $request['price_type'];
            $inquiry->start_time = $request['start_time'];
            $inquiry->end_time = $request['end_time'];
            $inquiry->start_date = date('Y-m-d', strtotime($request['start_date']));
            $inquiry->end_date = date('Y-m-d', strtotime($request['end_date']));
            $inquiry->length_of_term = $request['length_of_term'];
            $inquiry->remarks = $request['remarks'];
            $inquiry->detail_price = $request['detail_price'];
            $inquiry->usable_discount = $request['usable_discount'];
            $inquiry->discount_percentage = $request['discount_percentage'];
            $inquiry->discount_price = $request['discount_price'];
            $inquiry->tax_status = $request['tax_status'];
            $inquiry->start_date_counted = $request['start_date_counted'];
            $inquiry->total_price = $request['total_price'];
            $inquiry->total_tax_price = $request['total_tax_price'];
            $inquiry->total_service_charge = $request['total_service_charge'];
            $inquiry->total_additional_charge = $request['total_additional_charge'];
            $inquiry->total_service_charge_additional_charge = $request['total_service_charge_additional_charge'];
            $inquiry->total_tax_additional_charge = $request['total_tax_additional_charge'];
            $inquiry->security_deposit = $request['security_deposit'];
            $inquiry->quantity = $request['quantity'];
            $inquiry->term_of_payment = $request['term_of_payment'];
            $inquiry->free_term_booking = $request['free_term_booking'];
            $inquiry->term_notice_period = $request['term_notice_period'];

            switch ($status->action) {
                case "draft":
                    $inquiry->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $inquiry->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $inquiry->complete_by = Auth::user()->name;
                    break;
            }

            if ($inquiry->save()) {
                DB::table('inquiry_and_package')->where('inquiry_id', $id)->delete();
                if (!empty($request['package_id'])) {
                    for ($i = 0; $i < sizeof($request['package_id']); $i++) {
                        $inquiry
                            ->packages()
                            ->attach($request['package_id'][$i], [
                                'price_type' => $request['package_price_type'][$i],
                                'detail_price' => $request['package_detail_price'][$i],
                                'quantity' => $request['package_quantity'][$i],
                                'start_date' => date('Y-m-d', strtotime($request['package_start_date'][$i])),
                                'end_date' => date('Y-m-d', strtotime($request['package_end_date'][$i])),
                                'start_time' => $request['package_start_time'][$i],
                                'end_time' => $request['package_end_time'][$i],
                                'length_of_term' => $request['package_length_of_term'][$i]
                            ]);
                    }
                }

                DB::table('inquiry_and_room')->where('inquiry_id', $id)->delete();
                if (!empty($request['room_id'])) {
                    for ($i = 0; $i < sizeof($request['room_id']); $i++) {
                        $inquiry->rooms()->attach($request['room_id'][$i], ['detail_price' => $request['room_detail_price'][$i]]);
                    }
                }

                DB::table('inquiry_and_product')->where('inquiry_id', $id)->delete();
                if (!empty($request['other_product_id'])) {
                    for ($i = 0; $i < sizeof($request['other_product_id']); $i++) {
                        $inquiry->products()->attach($request['other_product_id'][$i], [
                            'detail_price' => $request['ac_detail_price'][$i],
                            'quantity' => $request['ac_quantity'][$i],
                            'start_date' => date('Y-m-d', strtotime($request['ac_start_date'][$i])),
                            'end_date' => date('Y-m-d', strtotime($request['ac_end_date'][$i])),
                            'start_time' => $request['ac_start_time'][$i],
                            'end_time' => $request['ac_end_time'][$i],
                            'length_of_term' => $request['ac_length_of_term'][$i]
                        ]);
                    }
                }

                $this->sendApprovalNotification($inquiry->employee_id, $inquiry->id);

                if ($status->action == "posting") {
                    if (SalesActivityController::create_by_inquiry($inquiry)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
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
        $inquiry = Inquiry::findOrFail($id);

        $status = $inquiry->status;
        if ($inquiry->status->name == 'posted') {
            $status = Status::where('name', 'void')->first();
        } else if ($inquiry->status->name == 'open') {
            $status = Status::where('name', 'discard')->first();
        }

        $active_status_id = array(1, 2, 4);

        if (sizeof($inquiry->booking) > 0) {
            if (sizeof($inquiry->booking->whereIn('status_id', $active_status_id)) > 0) {
                \Session::flash('error', 'Inquiry = ' . $inquiry->code . " can't be " . $status->name . ' because already used in other active transaction');
                return Redirect::to($this->url);
            }
        }

        $inquiry->status_id = $status->id;
        $inquiry->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch ($status->action) {
            case "discard":
                $inquiry->discard_by = Auth::user()->name;
                break;
            case "cancel":
                $inquiry->cancel_by = Auth::user()->name;
                break;
        }

        if ($inquiry->save()) {
            \Session::flash('success', 'Inquiry = ' . $inquiry->code . ' is ' . $status->name);
        } else {
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function print($id)
    {
        $data['inquiry'] = Inquiry::findOrFail($id);
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['default_currency'] = $this->default_currency;
        $data['company_name'] = $this->company_name;
        $data['company_address_1'] = $this->company_address_1;
        $data['company_address_2'] = $this->company_address_2;
        $data['company_phone'] = $this->company_phone;
        $data['company_fax'] = $this->company_fax;
        return view('pages.transaction.inquiry.print', $data);
    }

    public function get_by_id($id)
    {
        $inquiry = Inquiry::join('statuses', 'statuses.id', 'inquiries.status_id')
            ->join('employees', 'employees.id', 'inquiries.employee_id')
            ->join('customers', 'customers.id', 'inquiries.customer_id')
            ->leftJoin('referrals', 'referrals.id', 'inquiries.referral_id')
            ->leftJoin('agents', 'agents.id', 'inquiries.agent_id')
            ->select('inquiries.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name')
            ->where('inquiries.id', $id)
            ->first();
        return $inquiry;
    }

    public function get_by_param($location_id, $customer_id)
    {
        $active_status_id = array(1, 2);

        return Inquiry::where('location_id', $location_id)
            ->where('customer_id', $customer_id)
            ->whereIn('status_id', $active_status_id)
            ->get();
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

    public function get_parent_of_this_employee($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->parent_id != null) {
            array_push($this->parent_ids, $employee->parent_id);
            $this->get_parent_of_this_employee($employee->parent_id);
        }
    }

    public function sendApprovalNotification($employee_id, $inquiry_id)
    {
        $inquiry = Inquiry::findOrFail($inquiry_id);

        if($inquiry->status->name == "open"){
            $this->get_parent_of_this_employee($employee_id);

            $employees = Employee::whereIn('id', $this->parent_ids)->get();
            foreach ($employees as $no => $detail) {
                $check_exist_notification = Notification::where('url', $this->url . '/' . $inquiry_id . '/edit')
                    ->where('user_id', $detail->user->id)
                    ->where('read_status', 'N')
                    ->first();
                if ($check_exist_notification == null) {
                    $notification = new Notification;
                    $notification->user_id = $detail->user->id;
                    $notification->header = "Approve Inquiry No = " . $inquiry->code;
                    $notification->body = "Inquiry no = " . $inquiry->code . " has been created by " . $inquiry->employee->name . " at" . $inquiry->created_at;
                    $notification->url = $this->url . '/' . $inquiry_id . '/edit';
                    $notification->save();
                }
            }
        }else if($inquiry->status->name == "posted"){
            Notification::where('url', $this->url . '/' . $inquiry_id . '/edit')->update(['read_status' => 'Y']);
        }else{
            // Do Nothing
        }
    }

    public function datatables()
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $inquiries = Inquiry::join('statuses', 'statuses.id', 'inquiries.status_id')
            ->join('employees', 'employees.id', 'inquiries.employee_id')
            ->join('customers', 'customers.id', 'inquiries.customer_id')
            ->leftJoin('referrals', 'referrals.id', 'inquiries.referral_id')
            ->leftJoin('agents', 'agents.id', 'inquiries.agent_id')
            ->leftJoin('proformas', 'proformas.inquiry_id', 'inquiries.id')
            ->select('inquiries.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name', 'proformas.status_id as status')
            ->whereIn('employees.id', $this->ids)
            ->where('inquiries.status_id', \Request::get('status_id'))
            ->get();

        return DataTables::of($inquiries)
            ->editColumn('grand_total', function ($data) {
                $total_price = $data->total_price + $data->total_service_charge + $data->total_tax_price + $data->total_additional_charge + $data->total_service_charge_additional_charge + $data->total_tax_additional_charge;
                return number_format($total_price, 0, ',', '.');
            })
            ->addColumn('created_by', function ($data) {

                if ($data->draft_by != null) {
                    $created_by = $data->created_by;
                } else if ($data->posting_by != null) {
                    $created_by = $data->posting_by;
                } else if ($data->discard_by != null) {
                    $created_by = $data->discard_by;
                } else if ($data->complete_by != null) {
                    $created_by = $data->complete_by;
                } else {
                    $created_by = $data->cancel_by;
                }
                return $created_by;
            })
            ->make(true);
    }

    public function create_proforma(Request $request, $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'due_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return Redirect::to($this->url);
            \Session::flash('error', 'You must input field due date !!!');
        } else {
            $proforma = new Proforma;
            $proforma->status_id = $inquiry->status_id;
            $proforma->company_id = $request['company_id'];
            $proforma->location_id = $inquiry->location_id;
            $proforma->bank_account_id = $request['bank_account_id'];
            $proforma->customer_id = $inquiry->customer_id;
            $proforma->contact_id = $inquiry->contact_id;
            $proforma->inquiry_id = $inquiry->id;
            $proforma->code = HomeController::getTransactionCode('proformas', 'PRO', $inquiry->location_id);
            $proforma->detail_status = "N";
            $proforma->custom_status = "Y";
            $proforma->proforma_date = date('y-m-d');
            $proforma->due_date = date("Y-m-d", strtotime($request['due_date']));
            $proforma->desc = $inquiry->remarks;
            $proforma->payment_status = "NP";
	        $proforma->total_deposit = $inquiry->security_deposit;
            // Start : Counting price
            $total_additional_charge = 0;
            $total_service_charge_additional_charge = 0;
            $total_tax_additional_charge = 0;
            foreach($inquiry->products as $product){
                $detail_price = round($product->pivot->detail_price);
                
                $sub_total = $detail_price * $product->pivot->quantity * $product->pivot->length_of_term;

                if($inquiry->tax_status == "no_tax"){

                }else if($inquiry->tax_status == "exclude"){
                    if($inquiry->service_charge_status == "Y"){
                        $total_service_charge_additional_charge = $total_additional_charge * $this->service_charge;
                        $total_tax_additional_charge = $total_service_charge_additional_charge *$this->tax_percentage;
                    }else{
                        $total_tax_additional_charge = $total_additional_charge * $this->tax_percentage;
                    }
                }else if($inquiry->tax_status == "include"){
                    $total_service_charge_additional_charge = 0;
                    $total_tax_additional_charge = 0;
                }else{
                    $total_service_charge_additional_charge = 0;
                    $total_tax_additional_charge = 0;
                }

                $total_additional_charge = $total_additional_charge + $sub_total;
            }

            if ($inquiry->term_of_payment != null) {
                if ($inquiry->free_term_booking != null) {

                    $proforma->total_price = ($inquiry->total_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_additional_charge+$inquiry->total_service_charge_additional_charge;
                    $proforma->total_service_charge = $inquiry->total_service_charge + $inquiry->total_service_charge_additional_charge;
                    $proforma->total_tax_price = ($inquiry->total_tax_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_tax_additional_charge;

                } else {
                    $proforma->total_price = ($inquiry->total_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_additional_charge+$inquiry->total_service_charge_additional_charge;
                    $proforma->total_service_charge = $inquiry->total_service_charge + $inquiry->total_service_charge_additional_charge;
                    $proforma->total_tax_price = ($inquiry->total_tax_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_tax_additional_charge;
                }
            } else {
                $proforma->total_price = $inquiry->total_price;
                $proforma->total_service_charge = $inquiry->total_service_charge + $inquiry->total_service_charge_additional_charge;
                $proforma->total_tax_price = $inquiry->total_tax_price;
            }
            // End : Counting price

            if ($proforma->save()) {
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            } else {
                DB::rollBack();
                \Session::flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url);
        }
    }

    public function getCustomerContact($customer_id, $contact_id)
    {
        $return['customer'] = Customer::findOrFail($customer_id);
        $return['contact'] = Contact::findOrFail($contact_id);
        $return['contact']->birth_date = date('m/d/Y', strtotime($return['contact']->birth_date));

        return $return;
    }

    public function updateCustomerContact(Request $request, $customer_id, $contact_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $contact = Contact::findOrFail($contact_id);
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url)
                ->withErrors($validator)
                ->withInput();
        } else {
            $customer->nature_of_business_id = $request['nature_of_business_id'];
            $customer->customer_type = $request['customer_type'];
            $customer->name = $request['customer_name'];
            $customer->email = $request['customer_email'];
            $customer->phone = $request['customer_phone'];
            $customer->mobile_phone = $request['customer_mobile_phone'];
            $customer->fax = $request['customer_fax'];
            $customer->address = $request['customer_address'];
            $customer->country = $request['customer_country'];
            $customer->city = $request['customer_city'];
            $customer->zipcode = $request['customer_zipcode'];
            $customer->tax_number = $request['customer_tax_number'];
            $customer->updated_by = Auth::user()->name;
            if ($customer->save()) {
                $contact->honorific = $request['contact_honorific'];
                $contact->name = $request['contact_name'];
                $contact->email = $request['contact_email'];
                $contact->id_number = $request['contact_id_number'];
                $contact->phone = $request['contact_phone'];
                $contact->mobile_phone = $request['contact_mobile_phone'];
                if (!empty($request['contact_birth_date'])) {
                    $contact->birth_date = date('Y-m-d', strtotime($request['contact_birth_date']));
                }
                if ($contact->save()) {
                    \Session::flash('success', 'You are success in updating your data');
                } else {
                    \Session::flash('error', 'You are failed in updating your data !!!');
                }
            } else {
                \Session::flash('error', 'You are failed in updating your data !!!');
            }
            return Redirect::to($request['back_url']);
        }
    }
}
