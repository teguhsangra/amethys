<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\BankAccount;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class BankAccountController extends Controller
{
    private $url = 'bank_account';
    private $form_id = 'bank_account_form';
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
        return view('pages.master.bank_account.index', $data);
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
        return view('pages.master.bank_account.editor', $data);
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
            'account_no' => 'required|unique:bank_accounts',
            'account_name' => 'required',
            'bank_name' => 'required',
            'branch_code' => 'required',
            'swift_code' => 'required',
            'currency_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $bank_account = new BankAccount;
            $bank_account->account_no = $request['account_no'];
            $bank_account->account_name = $request['account_name'];
            $bank_account->bank_name = $request['bank_name'];
            $bank_account->branch_code = $request['branch_code'];
            $bank_account->swift_code = $request['swift_code'];
            $bank_account->currency_code = $request['currency_code'];
            $bank_account->created_by = Auth::user()->name;
            if($bank_account->save()){
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
        $data['bank_account'] = BankAccount::findOrFail($id);
        return view('pages.master.bank_account.detail', $data);
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
        $data['bank_account'] = BankAccount::findOrFail($id);
        return view('pages.master.bank_account.editor', $data);
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
        $bank_account = BankAccount::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'account_no' => 'required|unique:bank_accounts,account_no,'.$bank_account->id,
            'account_name' => 'required',
            'bank_name' => 'required',
            'branch_code' => 'required',
            'swift_code' => 'required',
            'currency_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $bank_account->account_no = $request['account_no'];
            $bank_account->account_name = $request['account_name'];
            $bank_account->bank_name = $request['bank_name'];
            $bank_account->branch_code = $request['branch_code'];
            $bank_account->swift_code = $request['swift_code'];
            $bank_account->currency_code = $request['currency_code'];
            $bank_account->updated_by = Auth::user()->name;
            if($bank_account->save()){
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
        $bank_account = BankAccount::findOrFail($id);
        if($bank_account->delete()){
            \Session::flash('success', 'You are success in deleting your data');
        }else{
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables(){
        $bank_accounts = BankAccount::get();

        return DataTables::of($bank_accounts)->make(true);
    }
}
