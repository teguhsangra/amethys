<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class SchedulerController extends Controller
{
    //

    public function Booking(){
        $bookings = Booking::join('statuses','statuses.id','bookings.status_id')
            ->join('employees','employees.id','bookings.employee_id')
            ->join('customers','customers.id','bookings.customer_id')
            ->leftJoin('referrals','referrals.id','bookings.referral_id')
            ->leftJoin('agents','agents.id','bookings.agent_id')
            ->leftJoin('inquiries','inquiries.id','bookings.inquiry_id')
            ->select('bookings.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name', 'inquiries.code as inquiry_code')
            ->where('is_main_agreement','Y')
            ->whereIn('employees.id', $this->ids)
            ->get();
        File::put($path,$contents);
    }
}
