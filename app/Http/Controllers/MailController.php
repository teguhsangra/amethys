<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
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
use App\Models\TechnicalMeeting;
use App\Models\TechnicalMeetingArea;
use App\Models\TechnicalMeetingAreaDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Deposit;
use App\Models\Complimentary;
use App\Models\Notification;
use App\Models\BankAccount;
use App\Models\Furniture;
use App\Models\DedicatedPhone;
use App\Models\MarketingMaterial;
use App\Models\SalesActivity;
use App\Models\Ticketing;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use PDF;
use Mail;
use Illuminate\Support\Facades\Crypt;

class MailController extends Controller
{

    private $tax_percentage = 0;
    private $service_charge = 0;
    private $has_free_booking = '';
    private $office_hour_start = 0;
    private $company_name = '';
    private $company_address_1 = '';
    private $company_address_2 = '';
    private $company_phone = '';
    private $company_fax = '';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $parameter_of_tax_percentage = ParameterSetting::where('name', 'tax_percentage')->first();
        $this->tax_percentage = $parameter_of_tax_percentage->double_value;
        $parameter_of_service_charge = ParameterSetting::where('name', 'service_charge')->first();
        $this->service_charge = $parameter_of_service_charge->double_value;
        $parameter_of_has_free_booking = ParameterSetting::where('name', 'has_free_booking')->first();
        $this->has_free_booking = $parameter_of_has_free_booking->string_value;
        $parameter_of_office_hour_start = ParameterSetting::where('name', 'office_hour_start')->first();
        $this->office_hour_start = $parameter_of_office_hour_start->int_value;
        $this->company_name = ParameterSetting::where('name', 'company_name')->first();
        $this->company_address_1 = ParameterSetting::where('name', 'company_address_1')->first();
        $this->company_address_2 = ParameterSetting::where('name', 'company_address_2')->first();
        $this->company_phone = ParameterSetting::where('name', 'company_phone')->first();
        $this->company_fax = ParameterSetting::where('name', 'company_fax')->first();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function printMailAgreement($id)
    {
        $ids = Crypt::decryptString($id);

        $data['booking'] = Booking::findOrFail($ids);
        $data['company_name'] = $this->company_name;
        $data['company_address_1'] = $this->company_address_1;
        $data['company_address_2'] = $this->company_address_2;
        $data['company_phone'] = $this->company_phone;
        $data['company_fax'] = $this->company_fax;
        $data['banks'] = BankAccount::all();

        foreach ($data['banks'] as $no => $detail) {
            $data['bank_account'] = $detail;
            if ($no == 0) {
                break;
            }
        }
        return view('pages.transaction.main_agreement.email', $data);
    }


    public static function ticketing_mail($ticketing_id){
        $data['ticketing'] = Ticketing::findOrFail($ticketing_id);

        Mail::send('pages.mail.ticketing', $data, function ($message) use ($data) {
            $message->from('info@rakomsis.com', 'Rakomsis');

            $message->to($data['ticketing']->customer->email)->subject($data['ticketing']->subject);
        });

        if (Mail::failures()) {
            \Session::flash('error', 'Email Sent Failed');
        } else {
            \Session::flash('success', 'Email Sent');
        }
    }
}
