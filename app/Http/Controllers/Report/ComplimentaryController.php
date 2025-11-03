<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Status;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Complimentary;
use App\Models\Booking;
use App\Models\BookingComplimentary;
use App\Models\BookingDetail;
use App\Exports\ComplimentaryExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Carbon\Carbon;

class ComplimentaryController extends Controller
{
    private $url = 'complimentary_report';
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
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['locations'] = \Auth::user()->location;

        $locations_id = $request->input('locations_id');
        $data['start_date'] = $request->input('start_date');
        $data['end_date'] = $request->input('end_date');

        if ($data['start_date'] == null && $data['end_date'] == null) {
            $data['start_date'] = Carbon::now()->format("Y-m-d");
            $data['end_date'] = Carbon::now()->format("Y-m-d");
        }

        if ($locations_id == null) {
            $locations_id = $data['locations'][0]->id;
        }

        $data['locations_id'] = $locations_id;

        return view('pages.report.complimentary.index', $data);
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

    public function datatables(Request $request)
    {

        $complimentary = Complimentary::get();
        return DataTables::of($complimentary)
            ->editColumn('total_use_complimentary', function ($data) use ($request) {
                $active_status_id = array(1, 2, 4);
                $location_id = $request['location_id'];
                $year = intval(date('Y', strtotime($request['start_date'])));
                $month = intval(date('m', strtotime($request['start_date'])));

                if ($location_id != '' || $location_id != null) {
                    $total_use = BookingComplimentary::join('complimentarys', 'complimentarys.id', 'booking_complimentaries.complimentary_id')
                        ->join('bookings', 'bookings.id', 'booking_complimentaries.booking_id')
                        ->where('bookings.location_id', $location_id)
                        ->where('booking_complimentaries.complimentary_id', $data->id)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->sum('booking_complimentaries.total_complimentary');
                } else {
                    $total_use = BookingComplimentary::join('complimentarys', 'complimentarys.id', 'booking_complimentaries.complimentary_id')
                        ->join('bookings', 'bookings.id', 'booking_complimentaries.booking_id')
                        ->where('booking_complimentaries.complimentary_id', $data->id)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->whereIn('bookings.status_id', $active_status_id)
                        ->sum('booking_complimentaries.total_complimentary');
                }




                return $total_use;
            })
            ->make(true);
    }

    public function exportToExcel(Request $request)
    {
        $location_id = $request['location_id'];
        $year = intval(date('Y', strtotime($request['start_date'])));
        $month = intval(date('m', strtotime($request['start_date'])));
        $active_status_id = array(1, 2, 4);

        // Start : Terminate SO Booking
        if ($location_id != null) {
            $data['booking_detail'] = BookingComplimentary::join('complimentarys', 'complimentarys.id', 'booking_complimentaries.complimentary_id')
                ->join('bookings', 'bookings.id', 'booking_complimentaries.booking_id')
                ->select('booking_complimentaries.*')
                ->where('bookings.location_id', $location_id)
                ->whereIn('bookings.status_id', $active_status_id)
                ->where('month', $month)
                ->where('year', $year)
                ->get();
        } else {
            $data['booking_detail']  = BookingComplimentary::join('complimentarys', 'complimentarys.id', 'booking_complimentaries.complimentary_id')
                ->join('bookings', 'bookings.id', 'booking_complimentaries.booking_id')
                ->select('booking_complimentaries.*')
                ->whereIn('bookings.status_id', $active_status_id)
                ->where('month', $month)
                ->where('year', $year)
                ->get();
        }
        // End : Terminate SO Booking


        return Excel::download(new ComplimentaryExport($data), 'complimentary_report_usage_' . date('Y_m_d_H_i_s') . '.xlsx');
    }
}
