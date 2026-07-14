<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Transaction\SalesActivityController;
use App\Models\ParameterSetting;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\RoomCategory;
use App\Models\Room;
use App\Models\NatureOfBusiness;
use App\Models\Complimentary;
use App\Models\Inquiry;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\BookingComplimentary;
use App\Models\Location;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\BankAccount;
use App\Models\DedicatedPhone;
use App\Models\Furniture;
use Carbon\Carbon;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class HotelController extends Controller
{
    private $url = 'hotel';
    private $form_id = 'hotel_form';
    private $table_name = 'bookings';
    private $prefix_name = 'LO';
    private $ids = array();
    private $office_hour_start = 0;
    private $office_hour_end = 0;
    private $after_office_hour_end = 0;
    private $hotel_start_time = 0;
    private $hotel_end_time = 0;
    private $total_mod_rounding = 0;
    private $company_name = '';
    private $company_address_1 = '';
    private $company_address_2 = '';
    private $company_phone = '';
    private $company_fax = '';
    private $tax_percentage = 0;
    private $service_charge = 0;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $parameter_of_office_hour_end = ParameterSetting::where('name', 'office_hour_end')->first();
        $this->office_hour_end = $parameter_of_office_hour_end->int_value;
        $parameter_of_after_office_hour_end = ParameterSetting::where('name', 'after_office_hour_end')->first();
        $this->after_office_hour_end = $parameter_of_after_office_hour_end->int_value;
        $parameter_of_hotel_start_time = ParameterSetting::where('name', 'hotel_start_time')->first();
        $this->hotel_start_time = $parameter_of_hotel_start_time->int_value;
        $parameter_of_hotel_end_time = ParameterSetting::where('name', 'hotel_end_time')->first();
        $this->hotel_end_time = $parameter_of_hotel_end_time->int_value;
        $parameter_of_total_mod_rounding = ParameterSetting::where('name', 'total_mod_rounding')->first();
        $this->total_mod_rounding = $parameter_of_total_mod_rounding->int_value;
        $this->company_name = ParameterSetting::where('name', 'company_name')->first();
        $this->company_address_1 = ParameterSetting::where('name', 'company_address_1')->first();
        $this->company_address_2 = ParameterSetting::where('name', 'company_address_2')->first();
        $this->company_phone = ParameterSetting::where('name', 'company_phone')->first();
        $this->company_fax = ParameterSetting::where('name', 'company_fax')->first();
    }

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
        $data['date'] = Carbon::now()->isoFormat('MM');
        $data['this_year'] = date('Y');
        $data['location'] = Location::all();
        $data['room_category'] = RoomCategory::all();
        $data['form_id'] = $this->form_id;
        $data['statuses'] = Status::all();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        
        return view('pages.transaction.hotel.index', $data);
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

        $room_category = RoomCategory::where('code', 'LO')->first();

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['office_hour_start'] = $this->office_hour_start;
        $data['office_hour_end'] = $this->office_hour_end;
        $data['after_office_hour_end'] = $this->after_office_hour_end;
        $data['hotel_start_time'] = $this->hotel_start_time;
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        $data['hotel_end_time'] = $this->hotel_end_time;
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['room_category_id'] = $room_category->id;

        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        $data['complimentaries'] = Complimentary::get();
        $data['other_products'] = Product::where('main_status', 'N')->get();
        $data['dedicated_phones'] = DedicatedPhone::leftJoin('booking_dedicated_phones', 'booking_dedicated_phones.dedicated_phone_id', 'dedicated_phones.id')
            ->where('booking_dedicated_phones.dedicated_phone_id', null)
            ->where('dedicated_phones.customer_id', null)
            ->select('dedicated_phones.*')
            ->get();
        $not_active_booking = DedicatedPhone::join('booking_dedicated_phones', 'booking_dedicated_phones.dedicated_phone_id', 'dedicated_phones.id')
            ->join('bookings', 'bookings.id', 'booking_dedicated_phones.booking_id')
            ->whereNotIn('bookings.status_id', array(1, 2, 4))
            ->where('dedicated_phones.customer_id', null)
            ->select('dedicated_phones.*')
            ->get();
        $data['dedicated_phones'] = $not_active_booking->merge($data['dedicated_phones']);
        $data['inquiries'] = Inquiry::join('inquiry_and_room', 'inquiry_and_room.inquiry_id', 'inquiries.id')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'inquiry_and_room.room_id')
            ->where('r_c_and_room.room_category_id', $room_category->id)
            ->whereIn('inquiries.status_id', $active_status_id)
            ->where('inquiries.type', 'room')
            ->whereIn('inquiries.employee_id', $this->ids)
            ->select('inquiries.*')
            ->distinct()
            ->get();
        $data['furniture'] = Furniture::all();

        return view('pages.transaction.hotel.editor', $data);
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
            $location_id = null;
            $referral_id = null;
            $agent_id = null;
            $customer_id = null;
            $contact_id = null;
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
                $room_category_id = $request['room_category_id'];
                // End : Logic for type (package, product, room)

                $type = "room";
                $price_type = $request['price_type'];
            }
            // End : Logic for using inquiry

            $status = Status::where('name', $request['status_name'])->first();

            $price_type = $request['price_type'];

            $booking = new Booking;
            $booking->status_id = $status->id;
            $booking->location_id = $location_id;
            $booking->employee_id = $request['employee_id'];
            $booking->inquiry_id = $inquiry_id;
            $booking->customer_id = $customer_id;
            $booking->contact_id = $contact_id;
            $booking->complimentary_id = $request['complimentary_id'];
            $booking->room_category_id = $room_category_id;
            $booking->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $request['location_id']);
            $booking->type = 'room';
            $booking->price_type = $price_type;
            $booking->is_main_agreement = $request['is_main_agreement'];
            $booking->holiday_status = $request['holiday_status'];
            $booking->breakfast_status = $request['breakfast_status'];
            $booking->customer_status = $request['customer_status'];
            $booking->start_time = $request['start_time'];
            $booking->end_time = $request['end_time'];
            $booking->start_date = date('Y-m-d', strtotime($request['start_date']));
            $booking->end_date = date('Y-m-d', strtotime($request['end_date']));
            $booking->signed_date = date('Y-m-d', strtotime($request['signed_date']));
            $booking->length_of_term = $request['length_of_term'];
            $booking->length_of_term_after_office = $request['length_of_term_after_office'];
            $booking->remarks = $request['remarks'];
            $booking->detail_price = $request['detail_price'];
            $booking->usable_discount = $request['usable_discount'];
            $booking->discount_percentage = $request['discount_percentage'];
            $booking->discount_price = $request['discount_price'];
            $booking->tax_status = $request['tax_status'];
            $booking->total_price = $request['total_price'];
            $booking->total_service_charge = $request['total_service_charge'];
            $booking->total_tax_price = $request['total_tax_price'];
            $booking->total_additional_charge = $request['total_additional_charge'];
            $booking->total_service_charge_additional_charge = $request['total_service_charge_additional_charge'];
            $booking->total_tax_additional_charge = $request['total_tax_additional_charge'];
            $booking->security_deposit = $request['security_deposit'];
            $booking->stamp_duty = $request['stamp_duty'];
            $booking->round_price = $request['round_price'];
            $booking->term_notice_period = $request['term_notice_period'];
            $booking->term_of_payment = $request['term_of_payment'];
            $booking->free_term_booking = $request['free_term_booking'];
            $booking->total_use_complimentary = $request['total_use_complimentary'];

            switch ($status->action) {
                case "draft":
                    $booking->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $booking->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $booking->complete_by = Auth::user()->name;
                    break;
            }

            if ($booking->save()) {
                if (!empty($request['room_id'])) {
                    for ($i = 0; $i < sizeof($request['room_id']); $i++) {
                        $booking
                            ->rooms()
                            ->attach($request['room_id'][$i], [
                                'detail_price' => $request['room_detail_price'][$i],
                                'complimentary_id' => $request['complimentary_id'],
                                'detail_use_complimentary' => $request['detail_use_complimentary'][$i]
                            ]);
                    }
                }

                if (!empty($request['other_product_id'])) {
                    for ($i = 0; $i < sizeof($request['other_product_id']); $i++) {
                        $booking->products()->attach($request['other_product_id'][$i], [
                            'detail_price' => $request['ac_detail_price'][$i],
                            'quantity' => $request['ac_quantity'][$i],
                            'start_date' => date('Y-m-d', strtotime($request['ac_start_date'][$i])),
                            'end_date' => date('Y-m-d', strtotime($request['ac_end_date'][$i])),
                            'start_time' => $request['ac_start_time'][$i],
                            'end_time' => $request['ac_end_time'][$i],
                            'length_of_term' => $request['ac_length_of_term'][$i]
                        ]);
                    }
                    if ($status->action == "posting") {
                        if (BookingController::create_booking_order($booking)) {
                            // Do Nothing
                        } else {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    }
                }

                if (!empty($request['booking_complimentary_id'])) {
                    for ($i = 0; $i < sizeof($request['booking_complimentary_id']); $i++) {
                        if (!empty($request['total_complimentary'][$i]) ||  $request['total_complimentary'][$i] != 0) {
                            $booking
                                ->complimentarys()
                                ->attach($request['booking_complimentary_id'][$i], ['total_complimentary' => $request['total_complimentary'][$i]]);
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
                
                // Start : Create Notification
                if ($booking->is_main_agreement == "Y"){
                    if (BookingController::sendApprovalNotification($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Notification

                // Start : Create Deposit
                if ($status->action == "posting"){
                    if (BookingController::create_booking_deposit($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End Create Deposit

                // Start : Create Booking Detail
                if ($status->action == "posting") {
                    if (BookingController::create_booking_detail($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Booking Detail

                // Start : Create Complimentary
                if ($status->action == "posting") {
                    if (BookingController::booking_complimentary($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Complimentary

                // Start : Create Sales Activity
                if ($status->action == "posting") {
                    if (SalesActivityController::create_by_booking($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Sales Activity

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
        $data['email_url'] = 'booking/email/' . $id;
        $data['domicile_url'] = 'booking/domicile/' . $id;
        $data['term_condition_url'] = 'booking/term_condition/' . $id;
        $data['booking'] = Booking::findOrFail($id);
        $data['complimentaries'] = Complimentary::get();
        $data['company_name'] = $this->company_name->string_value;
        $data['company_address_1'] = $this->company_address_1->string_value;
        $data['company_address_2'] = $this->company_address_2->string_value;
        $data['company_phone'] = $this->company_phone->string_value;
        $data['company_fax'] = $this->company_fax->string_value;
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['banks'] = BankAccount::all();

        return view('pages.transaction.hotel.detail', $data);
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

        $room_category = RoomCategory::where('code', 'LO')->first();

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['office_hour_start'] = $this->office_hour_start;
        $data['office_hour_end'] = $this->office_hour_end;
        $data['after_office_hour_end'] = $this->after_office_hour_end;
        $data['hotel_start_time'] = $this->hotel_start_time;
        $data['hotel_end_time'] = $this->hotel_end_time;
        $data['total_mod_rounding'] = $this->total_mod_rounding;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['room_category_id'] = $room_category->id;
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;

        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['employees'] = Employee::whereIn('id', $this->ids)->get();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        $data['complimentaries'] = Complimentary::get();
        $data['other_products'] = Product::where('main_status', 'N')->get();
        $data['dedicated_phones'] = DedicatedPhone::leftJoin('booking_dedicated_phones', 'booking_dedicated_phones.dedicated_phone_id', 'dedicated_phones.id')
            ->where('booking_dedicated_phones.dedicated_phone_id', null)
            ->where('dedicated_phones.customer_id', null)
            ->select('dedicated_phones.*')
            ->get();
        $not_active_booking = DedicatedPhone::join('booking_dedicated_phones', 'booking_dedicated_phones.dedicated_phone_id', 'dedicated_phones.id')
            ->join('bookings', 'bookings.id', 'booking_dedicated_phones.booking_id')
            ->whereNotIn('bookings.status_id', array(1, 2, 4))
            ->where('dedicated_phones.customer_id', null)
            ->select('dedicated_phones.*')
            ->get();
        $data['dedicated_phones'] = $not_active_booking->merge($data['dedicated_phones']);
        $data['inquiries'] = Inquiry::join('inquiry_and_room', 'inquiry_and_room.inquiry_id', 'inquiries.id')
            ->join('r_c_and_room', 'r_c_and_room.room_id', 'inquiry_and_room.room_id')
            ->where('r_c_and_room.room_category_id', $room_category->id)
            ->whereIn('inquiries.status_id', $active_status_id)
            ->where('inquiries.type', 'room')
            ->whereIn('inquiries.employee_id', $this->ids)
            ->select('inquiries.*')
            ->distinct()
            ->get();
        $data['furniture'] = Furniture::all();

        $booking_dedicated_phone = DedicatedPhone::join('booking_dedicated_phones', 'booking_dedicated_phones.dedicated_phone_id', 'dedicated_phones.id')
            ->join('bookings', 'bookings.id', 'booking_dedicated_phones.booking_id')
            ->where('booking_dedicated_phones.booking_id', $id)
            ->select('dedicated_phones.*')
            ->get();

        $data['dedicated_phones'] = $booking_dedicated_phone->merge($data['dedicated_phones']);

        $booking = Booking::findOrFail($id);
        if ($booking->status_id == 2 || $booking->status_id == 3 || $booking->status_id == 4 || $booking->status_id == 5) {
            \Session::flash('warning', 'Sorry, you can not use booking no = ' . $booking->code . ', because this booking status = ' . $booking->status->name . ' !!!');
            return Redirect::to($this->url);
        }
        $data['booking'] = $booking;

        return view('pages.transaction.hotel.editor', $data);
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
            $booking = Booking::findOrFail($id);

            $contact_id = $booking->contact_id;
            $customer_id = $booking->customer_id;
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

            $status = Status::where('name', $request['status_name'])->first();

            $price_type = $request['price_type'];

            $booking->status_id = $status->id;
            $booking->contact_id = $contact_id;
            $booking->location_id = $request['location_id'];
            $booking->employee_id = $request['employee_id'];
            $booking->complimentary_id = $request['complimentary_id'];
            $booking->room_category_id = $request['room_category_id'];
            $booking->type = 'room';
            $booking->price_type = $price_type;
            $booking->is_main_agreement = $request['is_main_agreement'];
            $booking->holiday_status = $request['holiday_status'];
            $booking->customer_status = $request['customer_status'];
            $booking->breakfast_status = $request['breakfast_status'];
            $booking->start_time = $request['start_time'];
            $booking->end_time = $request['end_time'];
            $booking->start_date = date('Y-m-d', strtotime($request['start_date']));
            $booking->end_date = date('Y-m-d', strtotime($request['end_date']));
            $booking->signed_date = date('Y-m-d', strtotime($request['signed_date']));
            $booking->length_of_term = $request['length_of_term'];
            $booking->length_of_term_after_office = $request['length_of_term_after_office'];
            $booking->remarks = $request['remarks'];
            $booking->detail_price = $request['detail_price'];
            $booking->usable_discount = $request['usable_discount'];
            $booking->discount_percentage = $request['discount_percentage'];
            $booking->discount_price = $request['discount_price'];
            $booking->tax_status = $request['tax_status'];
            $booking->total_price = $request['total_price'];
            $booking->total_service_charge = $request['total_service_charge'];
            $booking->total_tax_price = $request['total_tax_price'];
            $booking->total_additional_charge = $request['total_additional_charge'];
            $booking->total_service_charge_additional_charge = $request['total_service_charge_additional_charge'];
            $booking->total_tax_additional_charge = $request['total_tax_additional_charge'];
            $booking->security_deposit = $request['security_deposit'];
            $booking->stamp_duty = $request['stamp_duty'];
            $booking->round_price = $request['round_price'];
            $booking->term_notice_period = $request['term_notice_period'];
            $booking->term_of_payment = $request['term_of_payment'];
            $booking->free_term_booking = $request['free_term_booking'];
            $booking->total_use_complimentary = $request['total_use_complimentary'];

            switch ($status->action) {
                case "draft":
                    $booking->draft_by = Auth::user()->name;
                    break;
                case "posting":
                    $booking->posting_by = Auth::user()->name;
                    break;
                case "complete":
                    $booking->complete_by = Auth::user()->name;
                    break;
            }

            if ($booking->save()) {

                if (!empty($request['room_id'])) {
                    DB::table('booking_and_room')->where('booking_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['room_id']); $i++) {
                        $booking
                            ->rooms()
                            ->attach($request['room_id'][$i], [
                                'detail_price' => $request['room_detail_price'][$i],
                                'complimentary_id' => $request['complimentary_id'],
                                'detail_use_complimentary' => $request['detail_use_complimentary'][$i]
                            ]);
                    }
                }

                if (!empty($request['other_product_id'])) {
                    DB::table('booking_and_product')->where('booking_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['other_product_id']); $i++) {
                        $booking->products()->attach($request['other_product_id'][$i], [
                            'detail_price' => $request['ac_detail_price'][$i],
                            'quantity' => $request['ac_quantity'][$i],
                            'start_date' => date('Y-m-d', strtotime($request['ac_start_date'][$i])),
                            'end_date' => date('Y-m-d', strtotime($request['ac_end_date'][$i])),
                            'start_time' => $request['ac_start_time'][$i],
                            'end_time' => $request['ac_end_time'][$i],
                            'length_of_term' => $request['ac_length_of_term'][$i]
                        ]);
                    }
                    if ($status->action == "posting") {
                        if (BookingController::create_booking_order($booking)) {
                            // Do Nothing
                        } else {
                            DB::rollBack();
                            \Session::flash('error', 'You are failed in inputing your data !!!');
                        }
                    }
                }

                if (!empty($request['booking_complimentary_id'])) {
                    DB::table('booking_and_complimentary')->where('booking_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['booking_complimentary_id']); $i++) {
                        if (!empty($request['total_complimentary'][$i]) ||  $request['total_complimentary'][$i] != 0) {
                            $booking
                                ->complimentarys()
                                ->attach($request['booking_complimentary_id'][$i], ['total_complimentary' => $request['total_complimentary'][$i]]);
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
                    DB::table('booking_dedicated_phones')->where('booking_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['dedicated_phone_id']); $i++) {
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
                
                // Start : Create Notification
                if ($booking->is_main_agreement == "Y"){
                    if (BookingController::sendApprovalNotification($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Notification

                // Start : Create Deposit
                if ($status->action == "posting"){
                    if (BookingController::create_booking_deposit($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End Create Deposit

                // Start : Create Booking Detail
                if ($status->action == "posting") {
                    BookingDetail::where('booking_id', $id)->delete();
                    if (BookingController::create_booking_detail($booking)) {
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
                    if (BookingController::booking_complimentary($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Complimentary

                // Start : Create Sales Activity
                if ($status->action == "posting") {
                    if (SalesActivityController::create_by_booking($booking)) {
                        // Do Nothing
                    } else {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                // End : Create Sales Activity

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
        $booking = Booking::findOrFail($id);

        $status = $booking->status;
        if ($booking->status->name == 'posted') {
            $status = Status::where('name', 'void')->first();
        } else if ($booking->status->name == 'open') {
            $status = Status::where('name', 'discard')->first();
        }

        $active_status_id = array(1, 2, 4);
        if (sizeof($booking->invoice->whereIn('status_id', $active_status_id)) > 0 || sizeof($booking->proforma->whereIn('status_id', $active_status_id)) > 0) {
            \Session::flash('error', 'Booking = ' . $booking->code . " can't be " . $status->name . ' because already used in other active transaction');
            return Redirect::to($this->url);
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

    public function print($id)
    {
        $data['booking'] = Booking::findOrFail($id);
        $data['complimentaries'] = Complimentary::get();
        $data['company_name'] = $this->company_name->string_value;
        $data['company_address_1'] = $this->company_address_1->string_value;
        $data['company_address_2'] = $this->company_address_2->string_value;
        $data['company_phone'] = $this->company_phone->string_value;
        $data['company_fax'] = $this->company_fax->string_value;
        $data['tax_percentage'] = $this->tax_percentage;
        $data['service_charge'] = $this->service_charge;
        $data['banks'] = BankAccount::all();

        foreach ($data['banks'] as $no => $detail) {
            $data['bank_account'] = $detail;
            if ($no == 0) {
                break;
            }
        }
        return view('pages.transaction.hotel.print', $data);
    }

    public function datatables()
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $bookings = Booking::join('statuses', 'statuses.id', 'bookings.status_id')
            ->join('employees', 'employees.id', 'bookings.employee_id')
            ->join('customers', 'customers.id', 'bookings.customer_id')
            ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
            ->select('bookings.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.id as customer_id', 'customers.name as customer_name')
            ->where('room_categories.code', 'LO')
            ->where('bookings.type', 'room')
            ->where('bookings.status_id', \Request::get('status_id'))
            ->whereIn('employees.id', $this->ids)
            ->get();

        return DataTables::of($bookings)
            ->editColumn('total_price', function ($data) {
                $total_price = $data->total_price + $data->total_service_charge + $data->total_tax_price + $data->total_additional_charge + $data->total_service_charge_additional_charge + $data->total_tax_additional_charge + $data->round_price;
                return number_format($total_price, 0, ',', '.');
            })
            ->editColumn('security_deposit', function ($data) {
                return number_format($data->security_deposit, 0, ',', '.');
            })
            ->editColumn('room_number', function ($data) {
                $room_ids = array();
                $rooms = Room::join('booking_and_room', 'booking_and_room.room_id', 'rooms.id')
                    ->where('booking_and_room.booking_id', $data->id)
                    ->select('rooms.room_number')
                    ->distinct()
                    ->get();
                foreach ($rooms as $detail) {
                    array_push($room_ids, $detail->room_number);
                }
                return $room_ids;
            })
            ->make(true);
    }
}
