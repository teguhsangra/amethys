<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Company;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class CompanyController extends Controller
{
    private $url = 'company';
    private $form_id = 'company_form';
    private $table_name = 'companies';
    private $prefix_name = 'COM';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->type == 'employee') {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        return view('pages.administrator.company.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->type == 'employee') {
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);
        return view('pages.administrator.company.editor', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:companies',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'booking_signatory' =>'required',
            'proforma_signatory' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $company = new Company;
            $company->code = $request['code'];
            $company->name = $request['name'];
            $company->address = $request['address'];
            $company->phone = $request['phone'];
            $company->fax = $request['fax'];
            $company->booking_signatory = $request['booking_signatory'];
            $company->proforma_signatory = $request['proforma_signatory'];
            $company->created_by = Auth::user()->name;
            if ($company->save()) {
                \Session::flash('success', 'You are success in inputing your data');
            } else {
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
        if (Auth::user()->type == 'employee') {
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['company'] = Company::findOrFail($id);
        return view('pages.administrator.company.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->type == 'employee') {
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['company'] = Company::findOrFail($id);
        return view('pages.administrator.company.editor', $data);
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
        $company = Company::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:companies,code,' . $company->id,
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'booking_signatory' =>'required',
            'proforma_signatory' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $company->code = $request['code'];
            $company->name = $request['name'];
            $company->address = $request['address'];
            $company->phone = $request['phone'];
            $company->fax = $request['fax'];
            $company->booking_signatory = $request['booking_signatory'];
            $company->proforma_signatory = $request['proforma_signatory'];
            $company->updated_by = Auth::user()->name;
            if ($company->save()) {
                \Session::flash('success', 'You are success in updating your data');
            } else {
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
        $company = Company::findOrFail($id);
        if ($company->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables()
    {
        $company = Company::get();

        return DataTables::of($company)->make(true);
    }
}
