<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\AgentCompany;
use App\Models\Agent;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Image;

class AgentController extends Controller
{
    protected $destinationPath = "/uploads/agent/";
    protected $main_path;
    private $url = 'agent';
    private $form_id = 'agent_form';
    private $table_name = 'agents';
    private $prefix_name = 'AG';
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
        return view('pages.master.agent.index', $data);
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
        $data['agent_companies'] = AgentCompany::get();
        return view('pages.master.agent.editor', $data);
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
            'code' => 'required|unique:agents',
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
            $agent = new Agent;
            $agent->agent_company_id = $request['agent_company_id'];
            $agent->code = $request['code'];
            $agent->job_title = $request['job_title'];
            $agent->name = $request['name'];
            $agent->email = $request['email'];
            $agent->phone = $request['phone'];
            $agent->mobile_phone = $request['mobile_phone'];
            $agent->address = $request['address'];
            $agent->country = $request['country'];
            $agent->city = $request['city'];
            $agent->zipcode = $request['zipcode'];
            $agent->tax_number = $request['tax_number'];
            $agent->bank_name = $request['bank_name'];
            $agent->bank_account = $request['bank_account'];
            $agent->commission = $request['commission'];
            $agent->file = $photo;
            $agent->created_by = Auth::user()->name;
            if ($agent->save()) {
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
        $data['agent'] = Agent::findOrFail($id);
        return view('pages.master.agent.detail', $data);
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
        $data['agent'] = Agent::findOrFail($id);
        $data['agent_companies'] = AgentCompany::get();
        return view('pages.master.agent.editor', $data);
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
        $agent = Agent::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:agents,code,' . $agent->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $agent->agent_company_id = $request['agent_company_id'];
            $agent->code = $request['code'];
            $agent->job_title = $request['job_title'];
            $agent->name = $request['name'];
            $agent->email = $request['email'];
            $agent->phone = $request['phone'];
            $agent->mobile_phone = $request['mobile_phone'];
            $agent->address = $request['address'];
            $agent->country = $request['country'];
            $agent->city = $request['city'];
            $agent->zipcode = $request['zipcode'];
            $agent->tax_number = $request['tax_number'];
            $agent->bank_name = $request['bank_name'];
            $agent->bank_account = $request['bank_account'];
            $agent->commission = $request['commission'];
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


                if ($agent->photo != null) {
                    if ($this->main_path == "local") {
                        File::Delete(public_path($photoName));
                    } else {
                        File::Delete($this->main_path . $photoName);
                    }
                }

                $agent->photo =  $this->destinationPath . '' . $photoName;
            }
            $agent->updated_by = Auth::user()->name;
            if ($agent->save()) {
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
        $agent = Agent::findOrFail($id);
        if ($agent->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables()
    {
        $agents = Agent::leftJoin('agent_companies', 'agent_companies.id', 'agents.agent_company_id')
            ->select('agents.*', 'agent_companies.name as ac_name')
            ->get();

        return DataTables::of($agents)->make(true);
    }
}
