<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Models\ParameterSetting;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Referral;
use App\Models\Agent;
use App\Models\Product;
use App\Models\Inquiry;
use App\Models\NatureOfBusiness;
use App\Models\RoomCategory;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\BookingComplimentary;
use App\Models\Deposit;
use App\Models\Complimentary;
use App\Models\Notification;
use App\Models\BankAccount;
use App\Models\Furniture;
use App\Models\DedicatedPhone;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use PDF;
use Mail;
use Illuminate\Support\Facades\Crypt;

class MainAgreementController extends Controller
{
    private $url = 'booking';
    private $form_id = 'booking_form';
    private $table_name = 'bookings';
    private $prefix_name = 'LA';
    private $ids = array();
    private $parent_ids = array();
    private $tax_percentage = 0;
    private $service_charge = 0;
    private $has_free_booking = '';
    private $office_hour_start = 0;
    private $company_name = '';
    private $company_address_1 = '';
    private $company_address_2 = '';
    private $company_phone = '';
    private $company_fax = '';
    private $director_name = '';
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
        $parameter_of_has_free_booking = ParameterSetting::where('name', 'has_free_booking')->first();
        $this->has_free_booking = $parameter_of_has_free_booking->string_value;
        $parameter_of_office_hour_start = ParameterSetting::where('name', 'office_hour_start')->first();
        $this->office_hour_start = $parameter_of_office_hour_start->int_value;
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
        $this->director_name = ParameterSetting::where('name', 'director_name')->first();
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
        return view('pages.transaction.main_agreement.index', $data);
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

        if (!empty(\Request::get('inquiry_id'))) {
            $inquiry = Inquiry::findOrFail(\Request::get('inquiry_id'));
            if ($inquiry->status_id == 3 || $inquiry->status_id == 4 || $inquiry->status_id == 5) {
                \Session::flash('warning', 'Sorry, you can not use inquiry no = ' . $inquiry->code . ', because this inquiry status = ' . $inquiry->status->name . ' !!!');
                return Redirect::to($this->url);
            }
            $data['inquiry'] = $inquiry;
        }

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['inquiries'] = Inquiry::whereIn('status_id', $active_status_id)->whereIn('employee_id', $this->ids)->get();
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['referrals'] = Referral::get();
        $data['products'] = Product::where('main_status', 'Y')->get();
        $data['other_products'] = Product::where('main_status', 'N')->get();
        $data['furniture'] = Furniture::all();
        $data['agents'] = Agent::get();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        $data['complimentaries'] = Complimentary::get();
        $data['room_categories'] = RoomCategory::where('code', '!=', 'LO')->get();
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['has_free_booking'] = $this->has_free_booking;
        $data['office_hour_start'] = $this->office_hour_start;
        $data['booking_complimentary'] = null;
        $data['dedicated_phones'] = DedicatedPhone::where('customer_id', null)->get();

        return view('pages.transaction.main_agreement.editor', $data);
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
            'employee_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $inquiry_id = null;
            $location_id = null;
            $referral_id = null;
            $agent_id = null;
            $customer_id = null;
            $contact_id = null;
            $room_id = null;
            $product_id = null;
            $package_id = null;
            $room_category_id = null;
            $customer_status = 'E';
            $type = '';
            $price_type = '';
            // Start : Logic for using inquiry
            if ($request['inquiry_id'] != null) {
                $inquiry = Inquiry::findOrFail($request['inquiry_id']);
                $inquiry_id = $inquiry->id;
                $location_id = $inquiry->location_id;
                $referral_id = $inquiry->referral_id;
                $agent_id = $inquiry->agent_id;
                $customer_id = $inquiry->customer_id;
                $contact_id = $inquiry->contact_id;
                $customer_status = $inquiry->customer_status;
                $package_id = $inquiry->package_id;
                $product_id = $inquiry->product_id;
                $room_category_id = $inquiry->room_category_id;
                $type = $inquiry->type;
                $price_type = $inquiry->price_type;
            } else {
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
                // End :Logic for customer

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

                $location_id = $request['location_id'];
                $referral_id = $request['referral_id'];
                $agent_id = $request['agent_id'];
                $customer_status = $request['customer_status'];

                // Start : Logic for type (package, product, room)
                if (!empty($request['package_id'])) {
                    $package_id = $request['package_id'];
                }
                if (!empty($request['product_id'])) {
                    $product_id = $request['product_id'];
                }
                $room_category_id = $request['room_category_id'];
                // End : Logic for type (package, product, room)

                $type = $request['type'];
                $price_type = $request['price_type'];
            }
            // End : Logic for using inquiry

            $status = Status::where('name', $request['status_name'])->first();

            $booking = new Booking;
            $booking->status_id = $status->id;
            $booking->location_id = $location_id;
            $booking->employee_id = $request['employee_id'];
            $booking->inquiry_id = $inquiry_id;
            $booking->referral_id = $referral_id;
            $booking->agent_id = $agent_id;
            $booking->customer_id = $customer_id;
            $booking->contact_id = $contact_id;
            $booking->room_id = $room_id;
            $booking->product_id = $product_id;
            $booking->package_id = $package_id;
            $booking->room_category_id = $room_category_id;
            $booking->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
            $booking->type = $type;
            $booking->price_type = $price_type;
            $booking->customer_status = $customer_status;
            $booking->start_time = $request['start_time'];
            $booking->end_time = $request['end_time'];
            $booking->start_date = date('Y-m-d', strtotime($request['start_date']));
            $booking->end_date = date('Y-m-d', strtotime($request['end_date']));
            $booking->signed_date = date('Y-m-d', strtotime($request['signed_date']));
            $booking->length_of_term = $request['length_of_term'];
            $booking->remarks = $request['remarks'];
            $booking->detail_price = $request['detail_price'];
            $booking->usable_discount = $request['usable_discount'];
            $booking->discount_percentage = $request['discount_percentage'];
            $booking->discount_price = $request['discount_price'];
            $booking->tax_status = $request['tax_status'];
            $booking->service_charge_status = $request['service_charge_status'];
            $booking->start_date_counted = "Y";
            $booking->total_price = $request['total_price'];
            $booking->total_service_charge = $request['total_service_charge'];
            $booking->total_tax_price = $request['total_tax_price'];
            $booking->security_deposit = $request['security_deposit'];
            $booking->stamp_duty = $request['stamp_duty'];
            $booking->dedicated_phone = $request['dedicated_phone'];
            $booking->dedicated_fax = $request['dedicated_fax'];
            $booking->quantity = $request['quantity'];
            $booking->term_of_payment = $request['term_of_payment'];
            $booking->free_term_booking = $request['free_term_booking'];
            $booking->term_notice_period = $request['term_notice_period'];

            switch ($status->action) {
                case "draft":
                    $booking->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $booking->posting_by = Auth::user()->name;
                    break;
            }

            if ($booking->save()) {
                if ($inquiry_id != null) {
                    $inquiry->status_id = 4;
                    $inquiry->save();
                }

                if (!empty($request['other_product_id'])) {
                    for ($i = 0; $i < sizeof($request['other_product_id']); $i++) {
                        $booking->products()->attach($request['other_product_id'][$i], ['detail_price' => $request['ac_detail_price'][$i], 'quantity' => $request['ac_quantity'][$i]]);
                    }
                    if ($status->action == "posting") {
                        if (BookingController::create_booking_order($booking, $request)) {
                            // Do Nothing
                        } else {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    }
                }

                if (!empty($request['dedicated_phone_id'])) {
                    for ($i = 0; $i < sizeof($request['dedicated_phone_id']); $i++) {
                        $booking->dedicated_phones()->attach($request['dedicated_phone_id'][$i]);

                        if ($status->action == "posting") {
                            $dedicated = DedicatedPhone::where('id', $request['dedicated_phone_id'][$i])->first();
                            $dedicated->customer_id = $customer_id;
                            if (!$dedicated->save()) {
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            }
                        }
                    }
                }

                if (!empty($request['furniture_id'])) {
                    for ($i = 0; $i < sizeof($request['furniture_id']); $i++) {
                        $booking->furniture()->attach($request['furniture_id'][$i], ['quantity' => $request['fu_quantity'][$i]]);
                    }
                }
                if (!empty($request['room_id'])) {
                    for ($i = 0; $i < sizeof($request['room_id']); $i++) {
                        $booking->rooms()->attach($request['room_id'][$i], ['detail_price' => $request['room_detail_price'][$i]]);
                    }
                }

                switch ($status->action) {
                    case "posting":
                    case "complete":
                        if ($request['security_deposit'] > 0) {
                            $deposit = new Deposit;
                            $deposit->status_id = $status->id;
                            $deposit->location_id = $booking->location_id;
                            $deposit->customer_id = $booking->customer_id;
                            $deposit->code = HomeController::getTransactionCode('deposits', 'DEP', $booking->location_id);
                            $deposit->type_security_deposit = 'IN';
                            $deposit->total_deposit = $request['security_deposit'];
                            if (!$deposit->save()) {
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            } else {
                                $booking->deposit_id = $deposit->id;
                                $booking->save();
                            }
                        }
                        break;
                }

                $month = date("m", strtotime($request['start_date']));
                $year = date("Y", strtotime($request['start_date']));
                $date = strtotime($request['start_date']);
                $total_discount_price = $request['discount_price'];
                $detail_service_charge = 0;
                $detail_tax_price = 0;
                $single_discount_price = 0;

                // Start : Create Configure Booking Detail & Complimentary
                switch ($price_type) {
                    case "yearly":
                        $total_booking = $request['length_of_term'];
                        $total_complimentary = $request['length_of_term'] * 12;
                        $length_of_detail = 1;
                        $end_date = date("Y-m-d", strtotime("+1 years", $date));
                        break;
                    case "monthly":
                        $total_booking = $request['length_of_term'];
                        $total_complimentary = $request['length_of_term'];
                        $length_of_detail = 1;
                        $end_date = date("Y-m-d", strtotime("+1 month", $date));
                        break;
                    case "daily":
                        $total_booking = $request['length_of_term'];
                        $total_complimentary = HomeController::dateDifference($request['start_date'], $request['end_date'], '%m');
                        $length_of_detail = 1;
                        if ($total_complimentary == 0) {
                            $total_complimentary = 1;
                        }
                        break;
                    default:
                        $total_booking = 1;
                        $total_complimentary = 1;
                        $length_of_detail = $request['length_of_term'];
                        break;
                }

                if ($total_discount_price > 0) {
                    if (!empty($request['room_id'])) {
                        $single_discount_price = ceil($total_discount_price / sizeof($request['room_id']));
                    } else {
                        $single_discount_price = ceil($total_discount_price / $booking->quantity);
                    }
                }
                // End : Create Configure Booking Detail & Complimentary

                // Start : Create Booking Detail
                if ($status->action == "posting") {
                    if (BookingController::create_booking_detail($booking, $total_booking, $length_of_detail, $date, $month, $year, $single_discount_price, $total_discount_price, $request)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Booking Detail

                // Start : Create Complimentary
                if ($status->action == "posting") {
                    if (BookingController::booking_complimentary($booking, $total_complimentary, $date, $month, $year, $request)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Complimentary

                if ($status->action == "draft") {
                    $this->sendApprovalNotification($booking->employee_id, $booking->id);
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
        $data['email_url'] = $this->url . '/email/' . $id;
        $data['domicile_url'] = $this->url . '/domicile/' . $id;
        $data['term_condition_url'] = $this->url . '/term_condition/' . $id;
        $data['booking'] = Booking::findOrFail($id);
        $data['complimentaries'] = Complimentary::get();
        $data['company_name'] = $this->company_name;
        $data['company_address_1'] = $this->company_address_1;
        $data['company_address_2'] = $this->company_address_2;
        $data['company_phone'] = $this->company_phone;
        $data['company_fax'] = $this->company_fax;
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['banks'] = BankAccount::all();

        foreach ($data['banks'] as $no => $detail) {
            $data['bank_account'] = $detail;
            if ($no == 0) {
                break;
            }
        }

        return view('pages.transaction.main_agreement.detail', $data);
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

        $data['booking'] = Booking::findOrFail($id);
        if ($data['booking']->status_id != 1) {
            \Session::flash('error', 'Sorry, you can not edit this booking !!!');
            return Redirect::to($this->url);
        }

        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['locations'] = Auth::user()->location;
        $data['inquiries'] = Inquiry::whereIn('employee_id', $this->ids)->get();
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['referrals'] = Referral::get();
        $data['products'] = Product::where('main_status', 'Y')->get();
        $data['other_products'] = Product::where('main_status', 'N')->get();
        $data['agents'] = Agent::get();
        $data['furniture'] = Furniture::all();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        $data['room_categories'] = RoomCategory::where('code', '!=', 'LO')->get();
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['has_free_booking'] = $this->has_free_booking;
        $data['office_hour_start'] = $this->office_hour_start;
        $data['complimentaries'] = Complimentary::get();
        $data['booking_complimentary'] = BookingComplimentary::where('month_sequence', 1)->get();
        $data['dedicated_phones'] = DedicatedPhone::where('customer_id', null)->get();

        return view('pages.transaction.main_agreement.editor', $data);
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
            'employee_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();

            $status = Status::where('name', $request['status_name'])->first();

            $booking = Booking::findOrFail($id);

            $free_term_booking = 0;

            $price_type = $booking->price_type;

            $booking->status_id = $status->id;
            $booking->start_time = $request['start_time'];
            $booking->end_time = $request['end_time'];
            $booking->start_date = date('Y-m-d', strtotime($request['start_date']));
            $booking->end_date = date('Y-m-d', strtotime($request['end_date']));
            $booking->signed_date = date('Y-m-d', strtotime($request['signed_date']));
            $booking->length_of_term = $request['length_of_term'];
            $booking->remarks = $request['remarks'];
            $booking->detail_price = $request['detail_price'];
            $booking->usable_discount = $request['usable_discount'];
            $booking->discount_percentage = $request['discount_percentage'];
            $booking->discount_price = $request['discount_price'];
            $booking->tax_status = $request['tax_status'];
            $booking->start_date_counted = "Y";
            $booking->total_price = $request['total_price'];
            $booking->total_service_charge = $request['total_service_charge'];
            $booking->total_tax_price = $request['total_tax_price'];
            $booking->quantity = $request['quantity'];
            $booking->term_of_payment = $request['term_of_payment'];
            $booking->free_term_booking = $request['free_term_booking'];
            $booking->term_notice_period = $request['term_notice_period'];
            $booking->security_deposit = $request['security_deposit'];

            switch ($status->action) {
                case "draft":
                    $booking->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $booking->posting_by = Auth::user()->name;
                    break;
            }

            if ($booking->save()) {
                DB::table('booking_and_product')->where('booking_id', $id)->delete();
                if (!empty($request['other_product_id'])) {
                    for ($i = 0; $i < sizeof($request['other_product_id']); $i++) {
                        $booking->products()->attach($request['other_product_id'][$i], ['detail_price' => $request['ac_detail_price'][$i], 'quantity' => $request['ac_quantity'][$i]]);
                    }

                    if ($status->action == "posting") {
                        if (BookingController::create_booking_order($booking, $request)) {
                            // Do Nothing
                        } else {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    }
                }

                if (!empty($request['furniture_id'])) {
                    DB::table('booking_and_furniture')->where('booking_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['furniture_id']); $i++) {
                        $booking->furniture()->attach($request['furniture_id'][$i], ['quantity' => $request['fu_quantity'][$i]]);
                    }
                }

                if (!empty($request['dedicated_phone_id'])) {
                    for ($i = 0; $i < sizeof($request['dedicated_phone_id']); $i++) {
                        DB::table('booking_dedicated_phones')->where('booking_id', $id)->delete();
                        $booking->dedicated_phones()->attach($request['dedicated_phone_id'][$i]);

                        if ($status->action == "posting") {
                            $dedicated = DedicatedPhone::where('id', $request['dedicated_phone_id'][$i])->first();
                            $dedicated->customer_id = $booking->customer_id;
                            if (!$dedicated->save()) {
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            }
                        }
                    }
                }

                if (!empty($request['room_id'])) {
                    DB::table('booking_and_room')->where('booking_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['room_id']); $i++) {
                        $booking->rooms()->attach($request['room_id'][$i], ['detail_price' => $request['room_detail_price'][$i]]);
                    }
                }

                switch ($status->action) {
                    case "posting":
                    case "complete":
                        if ($request['security_deposit'] > 0) {
                            $deposit = new Deposit;
                            $deposit->status_id = $status->id;
                            $deposit->location_id = $booking->location_id;
                            $deposit->customer_id = $booking->customer_id;
                            $deposit->code = HomeController::getTransactionCode('deposits', 'DEP', $booking->location_id);
                            $deposit->type_security_deposit = 'IN';
                            $deposit->total_deposit = $request['security_deposit'];
                            if (!$deposit->save()) {
                                DB::rollBack();
                                \Session::flash('error', 'You are failed in inputing your data !!!');
                            } else {
                                $booking->deposit_id = $deposit->id;
                                $booking->save();
                            }
                        }
                        break;
                }

                $month = date("m", strtotime($request['start_date']));
                $year = date("Y", strtotime($request['start_date']));
                $date = strtotime($request['start_date']);
                $total_discount_price = $request['discount_price'];
                $detail_service_charge = 0;
                $detail_tax_price = 0;
                $single_discount_price = 0;

                // Start : Create Configure Booking Detail & Complimentary
                switch ($price_type) {
                    case "yearly":
                        $total_booking = $request['length_of_term'];
                        $total_complimentary = $request['length_of_term'] * 12;
                        $length_of_detail = 1;
                        $end_date = date("Y-m-d", strtotime("+1 years", $date));
                        break;
                    case "monthly":
                        $total_booking = $request['length_of_term'];
                        $total_complimentary = $request['length_of_term'];
                        $length_of_detail = 1;
                        $end_date = date("Y-m-d", strtotime("+1 month", $date));
                        break;
                    case "daily":
                        $total_booking = $request['length_of_term'];
                        $total_complimentary = HomeController::dateDifference($request['start_date'], $request['end_date'], '%m');
                        $length_of_detail = 1;
                        if ($total_complimentary == 0) {
                            $total_complimentary = 1;
                        }
                        break;
                    default:
                        $total_booking = 1;
                        $total_complimentary = 1;
                        $length_of_detail = $request['length_of_term'];
                        break;
                }
                if ($total_discount_price > 0) {
                    if (!empty($request['room_id'])) {
                        $single_discount_price = ceil($total_discount_price / sizeof($request['room_id']));
                    } else {
                        $single_discount_price = ceil($total_discount_price / $booking->quantity);
                    }
                }
                // End : Create Configure Booking Detail & Complimentary

                // Start : Create Booking Detail
                if ($status->action == "posting") {
                    BookingDetail::where('booking_id', $id)->delete();
                    if (BookingController::create_booking_detail($booking, $total_booking, $length_of_detail, $date, $month, $year, $single_discount_price, $total_discount_price, $request)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Booking Detail

                // Start : Create Complimentary
                if ($status->action == "posting") {
                    BookingComplimentary::where('booking_id', $id)->delete();
                    for ($i = 1; $i <= $total_complimentary; $i++) {
                        if (!empty($request['complimentary_id'])) {
                            for ($j = 0; $j < sizeof($request['complimentary_id']); $j++) {
                                $counter_for_start_date = $i - 1;
                                $start_date = date("Y-m-d", strtotime("+" . $counter_for_start_date . " month", $date));
                                $end_date = date("Y-m-d", strtotime("+" . $i . " month", $date));
                                if (!empty($request['total_complimentary'][$j]) ||  $request['total_complimentary'][$j] != 0) {
                                    $booking_complimentary = new BookingComplimentary;
                                    $booking_complimentary->booking_id = $booking->id;
                                    $booking_complimentary->complimentary_id = $request['complimentary_id'][$j];
                                    $booking_complimentary->total_complimentary = $request['total_complimentary'][$j];
                                    $booking_complimentary->month = $month;
                                    $booking_complimentary->year = $year;
                                    $booking_complimentary->month_sequence = $i;
                                    $booking_complimentary->start_date = $start_date;
                                    $booking_complimentary->end_date = $end_date;

                                    if (!$booking_complimentary->save()) {
                                        DB::rollBack();
                                    }

                                    $month++;
                                    if ($month > 12) {
                                        $month = $month - 12;
                                        $year++;
                                    }
                                }
                            }
                        }
                    }
                }
                // End : Create Complimentary

                if ($status->action == "draft") {
                    $this->sendApprovalNotification($booking->employee_id, $booking->id);
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
        $booking = Booking::findOrFail($id);

        $status = $booking->status;
        if ($booking->status->name == 'posting') {
            $status = Status::where('name', 'void')->first();
        } else if ($booking->status->name == 'open') {
            $status = Status::where('name', 'discard')->first();
        }

        $active_status_id = array(1, 2, 4);
        if (sizeof($booking->invoice) > 0) {
            if (sizeof($booking->invoice->whereIn('status_id', $active_status_id)) > 0) {
                \Session::flash('error', 'Booking = ' . $booking->code . " can't be " . $status->name . ' because already used in other active transaction');
                return Redirect::to($this->url);
            }
        }

        if (sizeof($booking->proforma) > 0) {
            if (sizeof($booking->proforma->whereIn('status_id', $active_status_id)) > 0) {
                \Session::flash('error', 'Booking = ' . $booking->code . " can't be " . $status->name . ' because already used in other active transaction');
                return Redirect::to($this->url);
            }
        }

        if (sizeof($booking->deposit) > 0) {
            if (sizeof(sizeof($booking->deposit->whereIn('status_id', $active_status_id)) > 0)) {
                \Session::flash('error', 'Booking = ' . $booking->code . " can't be " . $status->name . ' because already used in other active transaction');
                return Redirect::to($this->url);
            }
        }

        $booking->status_id = $status->id;
        $booking->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch ($status->action) {
            case "discard":
                $booking->discard_by = Auth::user()->name;
                break;
            case "cancel":
                $booking->cancel_by = Auth::user()->name;
                break;
        }

        if ($booking->save()) {
            \Session::flash('success', 'Booking = ' . $booking->code . ' is ' . $status->name);
        } else {
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function print($id)
    {
        $data['booking'] = Booking::findOrFail($id);
        $data['company_name'] = $this->company_name;
        $data['company_address_1'] = $this->company_address_1;
        $data['company_address_2'] = $this->company_address_2;
        $data['company_phone'] = $this->company_phone;
        $data['company_fax'] = $this->company_fax;
        $data['banks'] = BankAccount::all();
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;

        foreach ($data['banks'] as $no => $detail) {
            $data['bank_account'] = $detail;
            if ($no == 0) {
                break;
            }
        }
        return view('pages.transaction.main_agreement.print', $data);
    }

    public function printmail($id)
    {
        $ids = Crypt::decryptString($id);
        $data['booking'] = Booking::findOrFail($ids);

        return view('pages.transaction.main_agreement.email', $data);
    }

    public function get_by_id($id)
    {
        $inquiry = Booking::join('statuses', 'statuses.id', 'bookings.status_id')
            ->join('employees', 'employees.id', 'bookings.employee_id')
            ->join('customers', 'customers.id', 'bookings.customer_id')
            ->leftJoin('referrals', 'referrals.id', 'bookings.referral_id')
            ->leftJoin('agents', 'agents.id', 'bookings.agent_id')
            ->select('bookings.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name')
            ->where('bookings.id', $id)
            ->first();

        return $inquiry;
    }

    public function get_by_param($location_id, $customer_id)
    {
        $active_status_id = array(1, 2);

        return Booking::where('location_id', $location_id)
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

    public function sendApprovalNotification($employee_id, $booking_id)
    {
        $booking = Booking::findOrFail($booking_id);

        $this->get_parent_of_this_employee($employee_id);

        $employees = Employee::whereIn('id', $this->parent_ids)->get();
        foreach ($employees as $no => $detail) {
            $check_exist_notification = Notification::where('url', $this->url . '/' . $booking_id . '/edit')
                ->where('user_id', $detail->user->id)
                ->where('read_status', 'N')
                ->first();
            if ($check_exist_notification == null) {
                $notification = new Notification;
                $notification->user_id = $detail->user->id;
                $notification->header = "Approve Booking No = " . $booking->code;
                $notification->body = "Booking no = " . $booking->code . " has been created by " . $booking->employee->name . " at" . $booking->created_at;
                $notification->url = $this->url . '/' . $booking_id . '/edit';
                $notification->save();
            }
        }
    }

    public function datatables()
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $bookings = Booking::join('statuses', 'statuses.id', 'bookings.status_id')
            ->join('employees', 'employees.id', 'bookings.employee_id')
            ->join('customers', 'customers.id', 'bookings.customer_id')
            ->leftJoin('referrals', 'referrals.id', 'bookings.referral_id')
            ->leftJoin('agents', 'agents.id', 'bookings.agent_id')
            ->leftJoin('inquiries', 'inquiries.id', 'bookings.inquiry_id')
            ->select('bookings.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name', 'inquiries.code as inquiry_code')
            ->where('is_main_agreement', 'Y')
            ->whereIn('employees.id', $this->ids)
            ->get();

        return DataTables::of($bookings)
            ->editColumn('grand_total', function ($data) {
                return number_format($data->total_price + $data->total_service_charge + $data->total_tax_price, 0, ',', '.');
            })
            ->make(true);
    }

    public function sendEmail(Request $request, $id)
    {
        $data['data'] = Booking::findOrFail($id);
        $data['text'] = "Agreement";
        $data['company_name'] = $this->company_name;
        $ids = Crypt::encryptString($id);
        $data['url'] = $this->url . '/print/mail/' . $ids;

        Mail::send('pages.mail.index', $data, function ($message) use ($data) {
            $message->from('info@rakitek.com', 'rakitek');

            $message->to($data['data']->customer->email)->subject('Lease Agreement');
        });

        if (Mail::failures()) {
            \Session::flash('error', 'Failed');
        } else {
            \Session::flash('success', 'successfully sent an email');
        }

        return Redirect::to($this->url);
    }

    public function domicile(Request $request, $id)
    {
        $data['booking'] = Booking::findOrFail($id);
        $data['company_name'] = $this->company_name;
        $data['company_address_1'] = $this->company_address_1;
        $data['company_address_2'] = $this->company_address_2;
        $data['company_phone'] = $this->company_phone;
        $data['company_fax'] = $this->company_fax;
        $data['banks'] = BankAccount::all();
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['director_name'] = $this->director_name->string_value;
        foreach ($data['banks'] as $no => $detail) {
            $data['bank_account'] = $detail;
            if ($no == 0) {
                break;
            }
        }
        $data['status'] = $request['status'];
        return view('pages.transaction.main_agreement.domicile', $data);
    }

    public function term_condition($id)
    {
        $data['booking'] = Booking::findOrFail($id);
        $data['company_name'] = $this->company_name;
        $data['company_address_1'] = $this->company_address_1;
        $data['company_address_2'] = $this->company_address_2;
        $data['company_phone'] = $this->company_phone;
        $data['company_fax'] = $this->company_fax;
        $data['banks'] = BankAccount::all();
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['director_name'] = $this->director_name->string_value;
        foreach ($data['banks'] as $no => $detail) {
            $data['bank_account'] = $detail;
            if ($no == 0) {
                break;
            }
        }

        return view('pages.transaction.main_agreement.term_condition', $data);
    }
}
