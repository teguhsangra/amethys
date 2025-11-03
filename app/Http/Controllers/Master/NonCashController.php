<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\NonCash;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class NonCashController extends Controller
{
    private $url = 'non_cash';
    private $form_id = 'non_cash_form';
    private $table_name = 'non_cashes';
    private $prefix_name = 'NC';
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
        return view('pages.master.non_cash.index', $data);
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
        return view('pages.master.non_cash.editor', $data);
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
            'code' => 'required|unique:non_cashes',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $non_cash = new NonCash;
            $non_cash->code = $request['code'];
            $non_cash->name = $request['name'];
            $non_cash->has_bank = $request['has_bank'];
            $non_cash->has_card = $request['has_card'];
            $non_cash->created_by = Auth::user()->name;
            if($non_cash->save()){
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
        $data['non_cash'] = NonCash::findOrFail($id);
        return view('pages.master.non_cash.detail', $data);
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
        $data['non_cash'] = NonCash::findOrFail($id);
        return view('pages.master.non_cash.editor', $data);
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
        $non_cash = NonCash::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:non_cashes,code,'.$non_cash->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $non_cash->code = $request['code'];
            $non_cash->name = $request['name'];
            $non_cash->has_bank = $request['has_bank'];
            $non_cash->has_card = $request['has_card'];
            $non_cash->updated_by = Auth::user()->name;
            if($non_cash->save()){
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
        $non_cash = NonCash::findOrFail($id);
        if($non_cash->delete()){
            \Session::flash('success', 'You are success in deleting your data');
        }else{
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function get_by_id($id){
        return NonCash::findOrFail($id);
    }

    public function datatables(){
        $non_cashes = NonCash::get();

        return DataTables::of($non_cashes)->make(true);
    }
}
