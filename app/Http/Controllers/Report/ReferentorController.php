<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Status;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Referral;
use App\Models\Agent;
use App\Models\SalesTarget;
use App\Models\Booking;
use App\Exports\ReferentorReportExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class ReferentorController extends Controller
{
    private $url = 'referentor_report';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
    public function index(Request $request)
    {
        $active_status_id = array(1, 2, 4);
        $referral_achievement = array();
        $agent_achievement = array();
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;

        $data['locations'] = \Auth::user()->location;

        if ($request['location_id'] == null) {
            $request['location_id'] = $data['locations'][0]->id;
        }
        if ($request['year'] == null && $request['month'] == null) {
            $data['year'] = date('Y');
            $data['month'] = date('m');
        } else {
            $data['year'] = $request['year'];
            $data['month'] = $request['month'];
        }

        $first_day_of_month = date('Y-m-d', strtotime($data['year'] . '-' . $data['month'] . '-01'));
        $last_day_of_month = date('Y-m-t', strtotime($first_day_of_month));

        $data['referrals'] = Referral::orderBy('code', 'asc')->get();
        $data['agents'] = Agent::orderBy('code', 'asc')->get();

        foreach ($data['referrals'] as $no => $detail) {
            $total_booking = Booking::select(DB::raw('sum(total_price + total_tax_price) as grand_total'))
                ->whereIn('status_id', $active_status_id)
                ->where('referral_id', $detail->id)
                ->where('start_date', '>=', $first_day_of_month)
                ->where('start_date', '<=', $last_day_of_month)
                ->first();
            array_push($referral_achievement, $total_booking['grand_total']);
        }

        foreach ($data['agents'] as $no => $detail) {
            $total_booking = Booking::select(DB::raw('sum(total_price + total_tax_price) as grand_total'))
                ->whereIn('status_id', $active_status_id)
                ->where('agent_id', $detail->id)
                ->where('start_date', '>=', $first_day_of_month)
                ->where('start_date', '<=', $last_day_of_month)
                ->first();
            array_push($agent_achievement, $total_booking['grand_total']);
        }

        $data['location'] = Location::findOrFail($request['location_id']);
        $data['referral_achievement'] = $referral_achievement;
        $data['agent_achievement'] = $agent_achievement;

        return view('pages.report.referentor.index', $data);
    }

    public function exportToExcel(Request $request)
    {
        $active_status_id = array(1, 2, 4);
        $referral_achievement = array();
        $agent_achievement = array();
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;

        $data['locations'] = \Auth::user()->location;

        if ($request['location_id'] == null) {
            $request['location_id'] = $data['locations'][0]->id;
        }
        if ($request['year'] == null && $request['month'] == null) {
            $data['year'] = date('Y');
            $data['month'] = date('m');
        } else {
            $data['year'] = $request['year'];
            $data['month'] = $request['month'];
        }

        $first_day_of_month = date('Y-m-d', strtotime($data['year'] . '-' . $data['month'] . '-01'));
        $last_day_of_month = date('Y-m-t', strtotime($first_day_of_month));

        $data['referrals'] = Referral::orderBy('code', 'asc')->get();
        $data['agents'] = Agent::orderBy('code', 'asc')->get();

        foreach ($data['referrals'] as $no => $detail) {
            $total_booking = Booking::select(DB::raw('sum(total_price + total_tax_price) as grand_total'))
                ->whereIn('status_id', $active_status_id)
                ->where('referral_id', $detail->id)
                ->where('start_date', '>=', $first_day_of_month)
                ->where('start_date', '<=', $last_day_of_month)
                ->first();
            array_push($referral_achievement, $total_booking['grand_total']);
        }

        foreach ($data['agents'] as $no => $detail) {
            $total_booking = Booking::select(DB::raw('sum(total_price + total_tax_price) as grand_total'))
                ->whereIn('status_id', $active_status_id)
                ->where('agent_id', $detail->id)
                ->where('start_date', '>=', $first_day_of_month)
                ->where('start_date', '<=', $last_day_of_month)
                ->first();
            array_push($agent_achievement, $total_booking['grand_total']);
        }

        $data['location'] = Location::findOrFail($request['location_id']);
        $data['referral_achievement'] = $referral_achievement;
        $data['agent_achievement'] = $agent_achievement;

        return Excel::download(new ReferentorReportExport($data), 'RR_' . date('Y_m_d_H_i_s') . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
