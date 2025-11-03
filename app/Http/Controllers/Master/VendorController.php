<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\VendorCategory;
use App\Models\Vendor;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class VendorController extends Controller
{
    private $url = 'vendor';
    private $form_id = 'vendor_form';
    private $table_name = 'vendors';
    private $prefix_name = 'Ve';
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
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        return view('pages.master.vendor.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);
        $data['vendor_categories'] = VendorCategory::get();
        return view('pages.master.vendor.editor', $data);
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
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:vendors',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $vendor = new Vendor;
            $vendor->code = $request['code'];
            $vendor->name = $request['name'];
            $vendor->email = $request['email'];
            $vendor->phone = $request['phone'];
            $vendor->mobile_phone = $request['mobile_phone'];
            $vendor->address = $request['address'];
            $vendor->country = $request['country'];
            $vendor->city = $request['city'];
            $vendor->zipcode = $request['zipcode'];
            $vendor->tax_number = $request['tax_number'];
            $vendor->bank_name = $request['bank_name'];
            $vendor->bank_account = $request['bank_account'];
            $vendor->created_by = Auth::user()->name;
            if($vendor->save()){
                for($i=0; $i<sizeof($request['vendor_category_id']); $i++){
                    $vendor->vendor_category()->attach($request['vendor_category_id'][$i]);
                }
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
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['vendor'] = Vendor::findOrFail($id);
        return view('pages.master.vendor.detail', $data);
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
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url.'/'.$id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['vendor'] = Vendor::findOrFail($id);
        $data['vendor_categories'] = VendorCategory::get();
        return view('pages.master.vendor.editor', $data);
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
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $vendor = Vendor::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:vendors,code,'.$vendor->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $vendor->code = $request['code'];
            $vendor->name = $request['name'];
            $vendor->email = $request['email'];
            $vendor->phone = $request['phone'];
            $vendor->mobile_phone = $request['mobile_phone'];
            $vendor->address = $request['address'];
            $vendor->country = $request['country'];
            $vendor->city = $request['city'];
            $vendor->zipcode = $request['zipcode'];
            $vendor->tax_number = $request['tax_number'];
            $vendor->bank_name = $request['bank_name'];
            $vendor->bank_account = $request['bank_account'];
            $vendor->updated_by = Auth::user()->name;
            if($vendor->save()){
                DB::table('v_c_and_vendor')->where('vendor_id', $id)->delete();
                for($i=0; $i<sizeof($request['vendor_category_id']); $i++){
                    $vendor->vendor_category()->attach($request['vendor_category_id'][$i]);
                }
                \Session::flash('success', 'You are success in updating your data');
            }else{
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
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $vendor = Vendor::findOrFail($id);
        if($vendor->delete()){
            \Session::flash('success', 'You are success in deleting your data');
        }else{
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables(){
        $vendors = Vendor::get();

        return DataTables::of($vendors)->make(true);
    }
}
