<?php

namespace App\Http\Controllers\Report;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Status;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Package;
use App\Models\SalesTarget;
use App\Models\Inquiry;
use App\Models\Booking;
use App\Exports\MarketingReportExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class MarketingController extends Controller
{
    private $url = 'marketing_report';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $active_status_id = array(1, 2, 4);
        $total_achievement = array();
        $sales_achievement = array();
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;

        $data['locations'] = \Auth::user()->location;
        $data['employees'] = Employee::get();
        $data['year'] = date('Y');
        $data['month'] = date('m');

        return view('pages.report.marketing.index', $data);
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

    public function datatables_kpi(Request $request){
        $employee_id = $request['employee_id'];
        $request['active_status'] = array(2, 4);
        $request['not_active_status'] = array(3, 5);
        
        if(!empty($employee_id)){
            $employees = Employee::where('id', $employee_id)->get();
        }else{
            $employees = Employee::get();
        }

        return DataTables::of($employees)
            ->editColumn('total_prospect', function ($data) use ($request) {
                $location_id = $request['location_id'];
                $start_date = date('Y-m-d', strtotime($request['start_date']));
                $end_date = date('Y-m-d', strtotime($request['end_date']));

                $total = DB::table('prospects')
                    ->where('employee_id', $data->id)
                    ->where('created_at','>=', $start_date. ' 00:00:00')
                    ->where('created_at','<=', $end_date. ' 23:59:59')
                    ->count();

                return $total;
            })
            ->editColumn('total_inquiry', function ($data) use ($request)  {
                $location_id = $request['location_id'];
                $start_date = date('Y-m-d', strtotime($request['start_date']));
                $end_date = date('Y-m-d', strtotime($request['end_date']));

                if($location_id != null){
                    $total = DB::table('inquiries')
                        ->where('location_id', $location_id)
                        ->where('employee_id', $data->id)
                        ->where('created_at','>=', $start_date. ' 00:00:00')
                        ->where('created_at','<=', $end_date. ' 23:59:59')
                        ->whereIn('status_id', $request['active_status'])
                        ->count();
                }else{
                    $total = DB::table('inquiries')
                        ->where('employee_id', $data->id)
                        ->where('created_at','>=', $start_date. ' 00:00:00')
                        ->where('created_at','<=', $end_date. ' 23:59:59')
                        ->whereIn('status_id', $request['active_status'])
                        ->count();
                }

                return $total;
            })
            ->editColumn('total_new_vo', function ($data) use ($request)  {
                $location_id = $request['location_id'];
                $start_date = date('Y-m-d', strtotime($request['start_date']));
                $end_date = date('Y-m-d', strtotime($request['end_date']));

                if($location_id != null){
                    $total = DB::table('bookings')
                        ->where('location_id', $location_id)
                        ->where('employee_id', $data->id)
                        ->where('type', 'product')
                        ->where('is_renewal', 'N')
                        ->where('start_date','>=', $start_date)
                        ->where('start_date','<=', $end_date)
                        ->whereIn('status_id', $request['active_status'])
                        ->count();
                }else{
                    $total = DB::table('bookings')
                        ->where('employee_id', $data->id)
                        ->where('type', 'product')
                        ->where('is_renewal', 'N')
                        ->where('start_date','>=', $start_date)
                        ->where('start_date','<=', $end_date)
                        ->whereIn('status_id', $request['active_status'])
                        ->count();
                }

                return $total;
            })
            ->editColumn('total_renew_vo', function ($data) use ($request)  {
                $location_id = $request['location_id'];
                $start_date = date('Y-m-d', strtotime($request['start_date']));
                $end_date = date('Y-m-d', strtotime($request['end_date']));

                if($location_id != null){
                    $total = DB::table('bookings')
                        ->where('location_id', $location_id)
                        ->where('employee_id', $data->id)
                        ->where('type', 'product')
                        ->where('is_renewal', 'Y')
                        ->where('start_date','>=', $start_date)
                        ->where('start_date','<=', $end_date)
                        ->whereIn('status_id', $request['active_status'])
                        ->count();
                }else{
                    $total = DB::table('bookings')
                        ->where('employee_id', $data->id)
                        ->where('type', 'product')
                        ->where('is_renewal', 'Y')
                        ->where('start_date','>=', $start_date)
                        ->where('start_date','<=', $end_date)
                        ->whereIn('status_id', $request['active_status'])
                        ->count();
                }

                return $total;
            })
            ->editColumn('total_terminate_vo', function ($data) use ($request)  {
                $location_id = $request['location_id'];
                $start_date = date('Y-m-d', strtotime($request['start_date']));
                $end_date = date('Y-m-d', strtotime($request['end_date']));

                if($location_id != null){
                    $total = DB::table('bookings')
                        ->where('location_id', $location_id)
                        ->where('employee_id', $data->id)
                        ->where('type', 'product')
                        ->where('end_date','>=', $start_date)
                        ->where('end_date','<=', $end_date)
                        ->whereIn('status_id', $request['active_status'])
                        ->count();
                }else{
                    $total = DB::table('bookings')
                        ->where('employee_id', $data->id)
                        ->where('type', 'product')
                        ->where('end_date','>=', $start_date)
                        ->where('end_date','<=', $end_date)
                        ->whereIn('status_id', $request['active_status'])
                        ->count();
                }

                return $total;
            })
            ->editColumn('total_new_so', function ($data) use ($request)  {
                $location_id = $request['location_id'];
                $start_date = date('Y-m-d', strtotime($request['start_date']));
                $end_date = date('Y-m-d', strtotime($request['end_date']));

                if($location_id != null){
                    $total = DB::table('bookings')
                        ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('room_categories.code', 'SO')
                        ->where('bookings.location_id', $location_id)
                        ->where('bookings.employee_id', $data->id)
                        ->where('bookings.type', 'room')
                        ->where('bookings.is_renewal', 'N')
                        ->where('bookings.start_date','>=', $start_date)
                        ->where('bookings.start_date','<=', $end_date)
                        ->whereIn('bookings.status_id', $request['active_status'])
                        ->count();
                }else{
                    $total = DB::table('bookings')
                        ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('room_categories.code', 'SO')
                        ->where('bookings.employee_id', $data->id)
                        ->where('bookings.type', 'room')
                        ->where('bookings.is_renewal', 'N')
                        ->where('bookings.start_date','>=', $start_date)
                        ->where('bookings.start_date','<=', $end_date)
                        ->whereIn('bookings.status_id', $request['active_status'])
                        ->count();
                }

                return $total;
            })
            ->editColumn('total_renew_so', function ($data) use ($request)  {
                $location_id = $request['location_id'];
                $start_date = date('Y-m-d', strtotime($request['start_date']));
                $end_date = date('Y-m-d', strtotime($request['end_date']));

                if($location_id != null){
                    $total = DB::table('bookings')
                        ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('room_categories.code', 'SO')
                        ->where('bookings.location_id', $location_id)
                        ->where('bookings.employee_id', $data->id)
                        ->where('bookings.type', 'room')
                        ->where('bookings.is_renewal', 'Y')
                        ->where('bookings.start_date','>=', $start_date)
                        ->where('bookings.start_date','<=', $end_date)
                        ->whereIn('bookings.status_id', $request['active_status'])
                        ->count();
                }else{
                    $total = DB::table('bookings')
                        ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('room_categories.code', 'SO')
                        ->where('bookings.employee_id', $data->id)
                        ->where('bookings.type', 'room')
                        ->where('bookings.is_renewal', 'Y')
                        ->where('bookings.start_date','>=', $start_date)
                        ->where('bookings.start_date','<=', $end_date)
                        ->whereIn('bookings.status_id', $request['active_status'])
                        ->count();
                }

                return $total;
            })
            ->editColumn('total_terminate_so', function ($data) use ($request)  {
                $location_id = $request['location_id'];
                $start_date = date('Y-m-d', strtotime($request['start_date']));
                $end_date = date('Y-m-d', strtotime($request['end_date']));

                if($location_id != null){
                    $total = DB::table('bookings')
                        ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('room_categories.code', 'SO')
                        ->where('bookings.location_id', $location_id)
                        ->where('bookings.employee_id', $data->id)
                        ->where('bookings.type', 'room')
                        ->where('bookings.end_date','>=', $start_date)
                        ->where('bookings.end_date','<=', $end_date)
                        ->whereIn('bookings.status_id', $request['active_status'])
                        ->count();
                }else{
                    $total = DB::table('bookings')
                        ->join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                        ->where('room_categories.code', 'SO')
                        ->where('bookings.employee_id', $data->id)
                        ->where('bookings.type', 'room')
                        ->where('bookings.end_date','>=', $start_date)
                        ->where('bookings.end_date','<=', $end_date)
                        ->whereIn('bookings.status_id', $request['active_status'])
                        ->count();
                }

                return $total;
            })
            ->make(true);
    }

    public function datatables_achievement(Request $request){
        $month = $request['month'];
        $year = $request['year'];
        $request['active_status'] = array(2, 4);
        $request['not_active_status'] = array(3, 5);
        
        $employees = Employee::join('sales_targets', 'sales_targets.employee_id','employees.id')
            ->select('employees.*')
            ->whereIn('sales_targets.status_id', $request['active_status'])
            ->where('sales_targets.month', $month)
            ->where('sales_targets.year', $year)
            ->get();

        return DataTables::of($employees)
            ->editColumn('total_target', function ($data) use ($request) {
                $month = $request['month'];
                $year = $request['year'];
                $total_target = DB::table('sales_targets')
                    ->where('employee_id', $data->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->sum('total_target');

                return number_format($total_target, 0, ',', '.');
            })
            ->editColumn('total_target_vo', function ($data) use ($request) {
                $month = $request['month'];
                $year = $request['year'];
                $total_target_vo = DB::table('sales_targets')
                    ->where('employee_id', $data->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->sum('total_target_vo');

                return number_format($total_target_vo, 0, ',', '.');
            })
            ->editColumn('total_target_so', function ($data) use ($request) {
                $month = $request['month'];
                $year = $request['year'];
                $total_target_so = DB::table('sales_targets')
                    ->where('employee_id', $data->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->sum('total_target_so');

                return number_format($total_target_so, 0, ',', '.');
            })
            ->editColumn('total_achievement', function ($data) use ($request) {
                $month = $request['month'];
                $year = $request['year'];

                if($month < 10) $month = "0".$month;

                $first_date = date('Y-m-01', strtotime($year.'-'.$month.'-01'));
                $last_date = date('Y-m-t', strtotime($first_date));

                $total_booking = DB::table('bookings')
                    ->where('employee_id', $data->id)
                    ->where('start_date', '>=', $first_date)
                    ->where('start_date', '<=', $last_date)
                    ->whereIn('status_id', $request['active_status'])
                    ->sum(DB::raw('total_price + total_service_charge + total_tax_price + total_additional_charge + total_service_charge_additional_charge + total_tax_additional_charge + security_deposit + stamp_duty + round_price'));

                return number_format($total_booking, 0 , ',', '.');
            })
            ->editColumn('so_achievement', function ($data) use ($request) {
                $month = $request['month'];
                $year = $request['year'];

                if($month < 10) $month = "0".$month;

                $first_date = date('Y-m-01', strtotime($year.'-'.$month.'-01'));
                $last_date = date('Y-m-t', strtotime($first_date));

                $total_booking = DB::table('bookings')
                    ->where('employee_id', $data->id)
                    ->where('start_date', '>=', $first_date)
                    ->where('start_date', '<=', $last_date)
                    ->where('type', 'room')
                    ->whereIn('status_id', $request['active_status'])
                    ->count();

                return number_format($total_booking, 0 , ',', '.');
            })
            ->editColumn('vo_achievement', function ($data) use ($request) {
                $month = $request['month'];
                $year = $request['year'];

                if($month < 10) $month = "0".$month;

                $first_date = date('Y-m-01', strtotime($year.'-'.$month.'-01'));
                $last_date = date('Y-m-t', strtotime($first_date));

                $total_booking = DB::table('bookings')
                    ->where('employee_id', $data->id)
                    ->where('start_date', '>=', $first_date)
                    ->where('start_date', '<=', $last_date)
                    ->where('type', 'product')
                    ->whereIn('status_id', $request['active_status'])
                    ->count();

                return number_format($total_booking, 0 , ',', '.');
            })
            ->make(true);
    }

    public function exportToExcel(Request $request)
    {
        $employee_id = $request['employee_id'];
        $location_id = $request['location_id'];
        $start_date = date('Y-m-d', strtotime($request['start_date']));
        $end_date = date('Y-m-d', strtotime($request['end_date']));
        $type = $request['type'];
        $request['active_status'] = array(2, 4);
        $request['not_active_status'] = array(3, 5);
        $employee_ids = array();
        $bookings = null;

        if(!empty($employee_id)){
            $employees = Employee::where('id', $employee_id)->get();
        }else{
            $employees = Employee::get();
        }

        foreach($employees as $employee){
            array_push($employee_ids, $employee->id);
        }
        
        // Start : Inquiries
        if($location_id != null){
            $inquiries = Inquiry::where('location_id', $location_id)
                ->where('created_at','>=', $start_date. ' 00:00:00')
                ->where('created_at','<=', $end_date. ' 23:59:59')
                ->whereIn('employee_id', $employee_ids)
                ->whereIn('status_id', $request['active_status'])
                ->get();
        }else{
            $inquiries = Inquiry::where('created_at','>=', $start_date. ' 00:00:00')
                ->where('created_at','<=', $end_date. ' 23:59:59')
                ->whereIn('employee_id', $employee_ids)
                ->whereIn('status_id', $request['active_status'])
                ->get();
        }
        // End : Inquiries

        // Start : New VO Booking
        if($location_id != null){
            $new_vo_booking = Booking::where('location_id', $location_id)
                ->where('type', 'product')
                ->where('is_renewal', 'N')
                ->where('start_date','>=', $start_date)
                ->where('start_date','<=', $end_date)
                ->whereIn('employee_id', $employee_ids)
                ->whereIn('status_id', $request['active_status'])
                ->get();
        }else{
            $new_vo_booking = Booking::where('type', 'product')
                ->where('is_renewal', 'N')
                ->where('start_date','>=', $start_date)
                ->where('start_date','<=', $end_date)
                ->whereIn('employee_id', $employee_ids)
                ->whereIn('status_id', $request['active_status'])
                ->get();
        }
        // End : New VO Booking

        // Start : Renew VO Booking
        if($location_id != null){
            $renew_vo_booking = Booking::where('location_id', $location_id)
                ->where('type', 'product')
                ->where('is_renewal', 'Y')
                ->where('start_date','>=', $start_date)
                ->where('start_date','<=', $end_date)
                ->whereIn('employee_id', $employee_ids)
                ->whereIn('status_id', $request['active_status'])
                ->get();
        }else{
            $renew_vo_booking = Booking::where('type', 'product')
                ->where('is_renewal', 'Y')
                ->where('start_date','>=', $start_date)
                ->where('start_date','<=', $end_date)
                ->whereIn('employee_id', $employee_ids)
                ->whereIn('status_id', $request['active_status'])
                ->get();
        }
        // End : Renew VO Booking

        // Start : New SO Booking
        if($location_id != null){
            $new_so_booking = Booking::join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', 'SO')
                ->where('bookings.location_id', $location_id)
                ->where('bookings.type', 'room')
                ->where('bookings.is_renewal', 'N')
                ->where('bookings.start_date','>=', $start_date)
                ->where('bookings.start_date','<=', $end_date)
                ->whereIn('bookings.employee_id', $employee_ids)
                ->whereIn('bookings.status_id', $request['active_status'])
                ->get();
        }else{
            $new_so_booking = Booking::join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', 'SO')
                ->where('bookings.type', 'room')
                ->where('bookings.is_renewal', 'N')
                ->where('bookings.start_date','>=', $start_date)
                ->where('bookings.start_date','<=', $end_date)
                ->whereIn('bookings.employee_id', $employee_ids)
                ->whereIn('bookings.status_id', $request['active_status'])
                ->get();
        }
        // End : New SO Booking

        // Start : Renew SO Booking
        if($location_id != null){
            $renew_so_booking = Booking::join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', 'SO')
                ->where('bookings.location_id', $location_id)
                ->where('bookings.type', 'room')
                ->where('bookings.is_renewal', 'Y')
                ->where('bookings.start_date','>=', $start_date)
                ->where('bookings.start_date','<=', $end_date)
                ->whereIn('bookings.employee_id', $employee_ids)
                ->whereIn('bookings.status_id', $request['active_status'])
                ->get();
        }else{
            $renew_so_booking = Booking::join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', 'SO')
                ->where('bookings.type', 'room')
                ->where('bookings.is_renewal', 'Y')
                ->where('bookings.start_date','>=', $start_date)
                ->where('bookings.start_date','<=', $end_date)
                ->whereIn('bookings.employee_id', $employee_ids)
                ->whereIn('bookings.status_id', $request['active_status'])
                ->get();
        }
        // End : Renew SO Booking

        // Start : Terminate VO Booking
        if($location_id != null){
            $terminate_vo_booking = Booking::where('location_id', $location_id)
                ->where('type', 'product')
                ->where('end_date','>=', $start_date)
                ->where('end_date','<=', $end_date)
                ->whereIn('employee_id', $employee_ids)
                ->whereIn('status_id', $request['active_status'])
                ->get();
        }else{
            $terminate_vo_booking = Booking::where('type', 'product')
                ->where('end_date','>=', $start_date)
                ->where('end_date','<=', $end_date)
                ->whereIn('employee_id', $employee_ids)
                ->whereIn('status_id', $request['active_status'])
                ->get();
        }
        // End : Terminate VO Booking

        // Start : Terminate SO Booking
        if($location_id != null){
            $terminate_so_booking = Booking::join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', 'SO')
                ->where('bookings.location_id', $location_id)
                ->where('bookings.type', 'room')
                ->where('bookings.end_date','>=', $start_date)
                ->where('bookings.end_date','<=', $end_date)
                ->whereIn('bookings.employee_id', $employee_ids)
                ->whereIn('bookings.status_id', $request['active_status'])
                ->get();
        }else{
            $terminate_so_booking = Booking::join('room_categories', 'room_categories.id', 'bookings.room_category_id')
                ->where('room_categories.code', 'SO')
                ->where('bookings.type', 'room')
                ->where('bookings.end_date','>=', $start_date)
                ->where('bookings.end_date','<=', $end_date)
                ->whereIn('bookings.employee_id', $employee_ids)
                ->whereIn('bookings.status_id', $request['active_status'])
                ->get();
        }
        // End : Terminate SO Booking

        $bookings = $terminate_vo_booking->merge($terminate_so_booking);
        $bookings = $bookings->merge($new_vo_booking);
        $bookings = $bookings->merge($renew_vo_booking);
        $bookings = $bookings->merge($new_so_booking);
        $bookings = $bookings->merge($renew_so_booking);

        $data['inquiries'] = $inquiries;
        $data['bookings'] = $bookings;

        return Excel::download(new MarketingReportExport($data), 'MR_' . date('Y_m_d_H_i_s') . '.xlsx');
    }

    public function chart_kpi(Request $request){
        $return = array();
        $labels = array();
        $targets = array();
        $achievements = array();

        $location_id = $request['location_id'];
        $month = $request['month'];
        $year = $request['year'];
        $request['active_status'] = array(2, 4);
        $request['not_active_status'] = array(3, 5);

        if($month < 10) $month = "0".$month;

        $first_date = date('Y-m-01', strtotime($year.'-'.$month.'-01'));
        $last_date = date('Y-m-t', strtotime($first_date));
        
        $employees = Employee::join('sales_targets', 'sales_targets.employee_id','employees.id')
            ->select('employees.*')
            ->whereIn('sales_targets.status_id', $request['active_status'])
            ->where('sales_targets.month', $month)
            ->where('sales_targets.year', $year)
            ->get();

        foreach($employees as $employee){
            $target = 0;
            $achievement = 0;

            // Start : This is target for amethyst
            $total_target_vo = DB::table('sales_targets')
                ->where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->sum('total_target_vo');

            $total_target_so = DB::table('sales_targets')
                ->where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->sum('total_target_so');

            $total_vo = DB::table('bookings')
                ->where('employee_id', $employee->id)
                ->where('start_date', '>=', $first_date)
                ->where('start_date', '<=', $last_date)
                ->where('type', 'product')
                ->whereIn('status_id', $request['active_status'])
                ->count();

            $total_so = DB::table('bookings')
                ->join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                ->where('bookings.employee_id', $employee->id)
                ->where('bookings.start_date', '>=', $first_date)
                ->where('bookings.start_date', '<=', $last_date)
                ->where('bookings.type', 'room')
                ->whereIn('bookings.status_id', $request['active_status'])
                ->count();
            
            if($total_target_vo == 0 && $total_target_so == 0){
                // Code for put zero for targer
                $target = 0;
            }else{
                $target = 100;
                
                if($total_vo < $total_target_vo){
                    $achievement = round($total_vo/$total_target_vo*100);
                }else{
                    $achievement = 100;
                }

                if($total_so >= $total_target_so){
                    $achievement = 100;
                }
            }
            // End : This is target for amethyst
            

            array_push($labels, $employee->name);
            array_push($targets, $target);
            array_push($achievements, $achievement);
        }
        $return['labels'] = $labels;
        $return['targets'] = $targets;
        $return['achievements'] = $achievements;

        return $return;
    }
}
