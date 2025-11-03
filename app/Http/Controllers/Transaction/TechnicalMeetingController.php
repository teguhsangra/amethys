<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Area;
use App\Models\TechnicalMeeting;
use App\Models\TechnicalMeetingArea;
use App\Models\TechnicalMeetingAreaDetail;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class TechnicalMeetingController extends Controller
{
    private $url = 'technical_meeting';
    private $form_id = 'technical_meeting_form';
    private $ids = array();
    /**
     * Create a new controller instance.
     *
     * @return void
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
    public function index()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['employee'] = $employee;
        return view('pages.transaction.technical_meeting.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Redirect::to($this->url);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request['id'];
        $a_g_and_module = HomeController::getAccess($this->url);
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $technical_meeting_area = new TechnicalMeetingArea;
            $technical_meeting_area->technical_meeting_id = $id;
            $technical_meeting_area->area_id = $request['area_id'];
            if($technical_meeting_area->save()){
                \Session::flash('success', 'You are success in inputing your data');
            }else{
                \Session::flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url.'/'.$id.'/edit');
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
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['print_url'] = $this->url.'/print/'.$id;
        $data['technical_meeting'] = TechnicalMeeting::findOrFail($id);

        return view('pages.transaction.technical_meeting.detail', $data);
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
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $area_id = array();
        $technical_meeting_area = TechnicalMeetingArea::where('technical_meeting_id', $id)->get();
        foreach($technical_meeting_area as $detail){
            array_push($area_id, $detail->area_id);
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['technical_meeting'] = TechnicalMeeting::findOrFail($id);
        $data['areas'] = Area::whereNotIn('id',$area_id)->get();

        return view('pages.transaction.technical_meeting.editor', $data);
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
        return Redirect::to($this->url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $a_g_and_module = HomeController::getAccess($this->url);
            if($a_g_and_module == null){
                \Session::flash('error', 'You are not allowed to access this module !!!');
                return Redirect::to('profile');
            }
            $technical_meeting_area = TechnicalMeetingArea::findOrFail($id);
            $technical_meeting = $technical_meeting_area->technical_meeting;
            if($technical_meeting_area->delete()){
                \Session::flash('success', 'You are success in deleting your data');
            }else{
                \Session::flash('error', 'You are failed in deleting your data !!!');
            }
        }catch (Exception $e){
            \Session::flash('error', $e->getMessage());
        }
        return Redirect::to($this->url.'/'.$technical_meeting->id.'/edit');
    }

    public function addAreaDetail($technical_meeting_area_id){
        $technical_meeting_area_detail = new TechnicalMeetingAreaDetail;
        $technical_meeting_area_detail->technical_meeting_area_id = $technical_meeting_area_id;
        $technical_meeting_area_detail->save();

        return "true";
    }

    public function editAreaDetail(Request $request, $technical_meeting_area_id){
        $technical_meeting_area_detail = TechnicalMeetingAreaDetail::findOrFail($technical_meeting_area_id);
        switch($request['label_name']){
            case "name" : 
                $technical_meeting_area_detail->name = $request['detail_value'];
                break;
            case "desc" : 
                $technical_meeting_area_detail->desc = $request['detail_value'];
                break;
            default: break;
        }
        $technical_meeting_area_detail->save();

        return $technical_meeting_area_detail;
    }

    public function print($id){
        $data['technical_meeting'] = TechnicalMeeting::findOrFail($id);
        return view('pages.transaction.technical_meeting.print', $data);
    }

    public function datatables()
    {
        $technical_meetings = TechnicalMeeting::join('statuses','statuses.id','technical_meetings.status_id')
            ->join('locations','locations.id','technical_meetings.location_id')
            ->join('bookings','bookings.id','technical_meetings.booking_id')
            ->join('customers','customers.id','bookings.customer_id')
            ->select('technical_meetings.*', 'statuses.name as status_name', 'customers.name as customer_name', 'bookings.code as booking_code', 'bookings.start_date as booking_date')
            ->get();

        return DataTables::of($technical_meetings)->make(true);
    }

    public function datatables_area($technical_meeting_area_id){
        $technical_meeting_area_details = TechnicalMeetingAreaDetail::where('technical_meeting_area_id', $technical_meeting_area_id)->get();

        return DataTables::of($technical_meeting_area_details)->make(true);
    }
}
