<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\GuestComment;
use App\Models\Employee;
use App\Models\Loading;
use App\Models\Booking;
use App\Models\BookingGuestComment;
use App\Models\BookingGuestCommentDetail;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class BookingGuestCommentController extends Controller
{
    private $url = 'booking_guest_comment';
    private $form_id = 'booking_guest_comment_form';
    private $table_name = 'booking_guest_comments';
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
        return view('pages.transaction.booking_guest_comment.index', $data);
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
            'guest_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            DB::beginTransaction();
            $booking_guest_comment = new BookingGuestComment;
            $booking_guest_comment->booking_id = $id;
            $booking_guest_comment->guest_name = $request['guest_name'];
            $booking_guest_comment->comment_date = date('Y-m-d');
            if($booking_guest_comment->save()){
                for($i=0; $i < sizeof($request['guest_comment_id']); $i++){
                    $booking_guest_comment_detail = new BookingGuestCommentDetail;
                    $booking_guest_comment_detail->booking_guest_comment_id = $booking_guest_comment->id;
                    $booking_guest_comment_detail->guest_comment_id = $request['guest_comment_id'][$i];
                    $booking_guest_comment_detail->rating = $request['rating'][$i];
                    if(!$booking_guest_comment_detail->save()){
                        DB::rollBack();
                    }
                }
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            }else{
                DB::rollBack();
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
        $data['guest_comments'] = GuestComment::get();
        $data['main_agreement'] = Booking::findOrFail($id);

        return view('pages.transaction.booking_guest_comment.detail', $data);
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

        $data['id'] = $id;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['guest_comments'] = GuestComment::get();
        $data['main_agreement'] = Booking::findOrFail($id);

        return view('pages.transaction.booking_guest_comment.editor', $data);
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

    public function print($id){
        $data['guest_comments'] = GuestComment::get();
        $data['main_agreement'] = Booking::findOrFail($id);

        return view('pages.transaction.booking_guest_comment.print', $data);
    }

    public function datatables()
    {
        $active_status_id = array(2);

        $bookings = Booking::join('statuses','statuses.id','bookings.status_id')
            ->join('employees','employees.id','bookings.employee_id')
            ->join('customers','customers.id','bookings.customer_id')
            ->leftJoin('referrals','referrals.id','bookings.referral_id')
            ->leftJoin('agents','agents.id','bookings.agent_id')
            ->leftJoin('inquiries','inquiries.id','bookings.inquiry_id')
            ->select('bookings.*', 'statuses.name as status_name', 'employees.name as employee_name', 'employees.id as employee_id', 'customers.name as customer_name', 'referrals.name as referral_name', 'agents.name as agent_name', 'inquiries.code as inquiry_code')
            ->where('is_main_agreement','Y')
            ->whereIn('bookings.status_id', $active_status_id)
            ->get();

        return DataTables::of($bookings)
            ->editColumn('total_price', function ($data) {
                return number_format($data->total_price,0,',','.');
            })
            ->make(true);
    }
}
