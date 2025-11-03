<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ParameterSetting;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Booking;
use App\Models\Vendor;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use DataTables;
use Validator;
use Redirect;
use Image;
use Auth;
use DB;

class PurchaseOrderController extends Controller
{
    private $url = 'purchase_order';
    private $form_id = 'purchase_order_form';
    private $table_name = 'purchase_orders';
    private $prefix_name = 'PO';
    private $destinationPath = '/uploads/purchase_order/';
    protected $main_path;
    private $ids = array();
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $parameter_of_main_path = ParameterSetting::where('name','main_path')->first();
        $this->main_path = $parameter_of_main_path->string_value;
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
        $data['statuses'] = Status::all();
        return view('pages.transaction.purchase_order.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['vendors'] = Vendor::get();
        $data['main_agreements'] = Booking::where('is_main_agreement','Y')->whereIn('status_id', $active_status_id)->get();
        return view('pages.transaction.purchase_order.editor', $data);
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
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'vendor_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $status = Status::where('name', $request['status_name'])->first();

            $booking = Booking::findOrFail($request['booking_id']);

            DB::beginTransaction();

            $purchase_order = new PurchaseOrder;
            $purchase_order->status_id = $status->id;
            $purchase_order->location_id = $booking->location_id;
            $purchase_order->booking_id = $request['booking_id'];
            $purchase_order->vendor_id = $request['vendor_id'];
            $purchase_order->code = HomeController::getTransactionCode($this->table_name, $this->prefix_name, $booking->location_id);
            $purchase_order->total_price = $request['total_price'];
            $purchase_order->notes = $request['notes'];

            switch($status->action){
                case "draft" : $purchase_order->draft_by = Auth::user()->name;
                break;
                case "posting" : $purchase_order->posting_by = Auth::user()->name;
                break;
                case "complete" : $purchase_order->complete_by = Auth::user()->name;
                break;
            }

            if($purchase_order->save()){
                if(!empty($request['name'])){
                    for($i=0; $i < sizeof($request['name']); $i++){
                        $purchase_order_detail = new PurchaseOrderDetail;
                        $purchase_order_detail->purchase_order_id = $purchase_order->id;
                        $purchase_order_detail->name = $request['name'][$i];
                        $purchase_order_detail->quantity = $request['quantity'][$i];
                        $purchase_order_detail->detail_price = $request['detail_price'][$i];
                        if(!$purchase_order_detail->save()){
                            DB::rollBack();
                        }
                    }
                }
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            }else{
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
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['print_url'] = $this->url.'/print/'.$id;
        $data['purchase_order'] = PurchaseOrder::findOrFail($id);
        return view('pages.transaction.purchase_order.detail', $data);
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
        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url.'/'.$id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['purchase_order'] = PurchaseOrder::findOrFail($id);
        return view('pages.transaction.purchase_order.editor', $data);
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
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'vendor_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $status = Status::where('name', $request['status_name'])->first();

            $booking = Booking::findOrFail($request['booking_id']);

            DB::beginTransaction();

            $purchase_order = PurchaseOrder::findOrFail($id);
            $purchase_order->status_id = $status->id;
            $purchase_order->total_price = $request['total_price'];
            $purchase_order->notes = $request['notes'];

            if(!empty($request['payment_status'])){
                $purchase_order->payment_status = $request['payment_status'];
            }

            if(!empty($request['photo'])){
                $file = $request->file('photo');
                if ($request->hasFile('photo')){
                    $photoName = time().'.'.$file->getClientOriginalExtension();

                    if($this->main_path == "local"){
                        $path = public_path($this->destinationPath);
                    }else{
                        $path = $this->main_path.$this->destinationPath;
                    }
                    HomeController::check_exist_folder($path);
                    $path = $path.$photoName;

                    if($file->getSize() > 1000000){
                        Image::make($file->getRealPath())->resize(1024, 1024, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($path);
                    }else{
                        Image::make($file->getRealPath())->save($path);
                    }
                    $purchase_order->payment_receipt = $this->destinationPath.''.$photoName;
                }
            }

            switch($status->action){
                case "draft" : $purchase_order->draft_by = Auth::user()->name;
                break;
                case "posting" : $purchase_order->posting_by = Auth::user()->name;
                break;
                case "complete" : $purchase_order->complete_by = Auth::user()->name;
                break;
            }

            if($purchase_order->save()){
                PurchaseOrderDetail::where('purchase_order_id', $id)->delete();
                if(!empty($request['name'])){
                    for($i=0; $i < sizeof($request['name']); $i++){
                        $purchase_order_detail = new PurchaseOrderDetail;
                        $purchase_order_detail->purchase_order_id = $purchase_order->id;
                        $purchase_order_detail->name = $request['name'][$i];
                        $purchase_order_detail->quantity = $request['quantity'][$i];
                        $purchase_order_detail->detail_price = $request['detail_price'][$i];
                        if(!$purchase_order_detail->save()){
                            DB::rollBack();
                        }
                    }
                }
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
            }else{
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
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        if($a_g_and_module == null || $employee == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $discard_or_cancel_reason = \Request::get('discard_or_cancel_reason');
        $purchase_order = PurchaseOrder::findOrFail($id);
        $status = $purchase_order->status;
        if($purchase_order->status->name == 'posted'){
            $status = Status::where('name', 'void')->first();
        }else if($purchase_order->status->name == 'open'){
            $status = Status::where('name', 'discard')->first();
        }
        $purchase_order->status_id = $status->id;
        $purchase_order->discard_or_cancel_reason = $discard_or_cancel_reason;

        switch($status->action){
            case "discard" : $purchase_order->discard_by = Auth::user()->name;
            break;
            case "cancel" : $purchase_order->cancel_by = Auth::user()->name;
            break;
        }

        if($purchase_order->save()){
            \Session::flash('success', 'Prospect = '.$purchase_order->code.' is '.$status->name);
        }else{
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function print($id){
        $data['purchase_order'] = PurchaseOrder::findOrFail($id);
        return view('pages.transaction.purchase_order.print', $data);
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

    public function datatables(){
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;

        $purchase_orders = PurchaseOrder::join('statuses','statuses.id','purchase_orders.status_id')
            ->join('bookings','bookings.id','purchase_orders.booking_id')
            ->join('vendors','vendors.id','purchase_orders.vendor_id')
            ->join('customers','customers.id','bookings.customer_id')
            ->select('purchase_orders.*', 'statuses.name as status_name', 'customers.name as customer_name', 'bookings.code as booking_code', 'vendors.name as vendor_name')
            ->where('purchase_orders.status_id', \Request::get('status_id'))
            ->get();

        return DataTables::of($purchase_orders)
            ->editColumn('total_price', function ($data) {
                return number_format($data->total_price,0,',','.');
            })
            ->make(true);
    }
}
