<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\AgentCompany;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Image;

class AgentCompanyController extends Controller
{
    protected $destinationPath = "/uploads/agent_company/";
    protected $main_path;
    private $url = 'agent_company';
    private $form_id = 'agent_company_form';
    private $table_name = 'agent_companies';
    private $prefix_name = 'AgCo';
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
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        return view('pages.master.agent_company.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);
        return view('pages.master.agent_company.editor', $data);
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
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:agent_companies',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $file = $request->file('file');
            $photo = null;

            if ($request->hasFile('file')) {
                $photoName = time() . '.' . $file->getClientOriginalExtension();

                $path = public_path($this->destinationPath);
                HomeController::check_exist_folder($path);
                $file->move($path, $photoName);
                $photo =  $this->destinationPath . '' . $photoName;
            }

            $agent_company = new AgentCompany;
            $agent_company->code = $request['code'];
            $agent_company->name = $request['name'];
            $agent_company->email = $request['email'];
            $agent_company->phone = $request['phone'];
            $agent_company->fax = $request['fax'];
            $agent_company->address = $request['address'];
            $agent_company->country = $request['country'];
            $agent_company->city = $request['city'];
            $agent_company->zipcode = $request['zipcode'];
            $agent_company->commission = $request['commission'];
            $agent_company->file = $photo;
            $agent_company->created_by = Auth::user()->name;
            if ($agent_company->save()) {
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
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['agent_company'] = AgentCompany::findOrFail($id);
        return view('pages.master.agent_company.detail', $data);
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
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['agent_company'] = AgentCompany::findOrFail($id);
        return view('pages.master.agent_company.editor', $data);
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
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $agent_company = AgentCompany::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:agent_companies,code,' . $agent_company->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {

            $agent_company->code = $request['code'];
            $agent_company->name = $request['name'];
            $agent_company->email = $request['email'];
            $agent_company->phone = $request['phone'];
            $agent_company->fax = $request['fax'];
            $agent_company->address = $request['address'];
            $agent_company->country = $request['country'];
            $agent_company->city = $request['city'];
            $agent_company->zipcode = $request['zipcode'];
            $agent_company->commission = $request['commission'];
            $file = $request->file('file');
            if ($request->hasFile('file')) {
                $photoName = time() . '.' . $file->getClientOriginalExtension();

                if ($this->main_path == "local") {
                    $path = public_path($this->destinationPath);
                } else {
                    $path = $this->main_path . $this->destinationPath;
                }
                HomeController::check_exist_folder($path);
                $file->move($path, $photoName);


                if ($agent_company->photo != null) {
                    if ($this->main_path == "local") {
                        File::Delete(public_path($photoName));
                    } else {
                        File::Delete($this->main_path . $photoName);
                    }
                }

                $agent_company->photo =  $this->destinationPath . '' . $photoName;
            }
            $agent_company->updated_by = Auth::user()->name;
            if ($agent_company->save()) {
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
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $agent_company = AgentCompany::findOrFail($id);
        if ($agent_company->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables()
    {
        $agent_companies = AgentCompany::get();

        return DataTables::of($agent_companies)->make(true);
    }
}
