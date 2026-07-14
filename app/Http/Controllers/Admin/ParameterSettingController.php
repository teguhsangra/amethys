<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ParameterSetting;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class ParameterSettingController extends Controller
{
    private $url = 'parameter_setting';
    private $form_id = 'parameter_setting_form';
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
        if(Auth::user()->type == 'employee'){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        return view('pages.administrator.parameter_setting.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->type == 'employee'){
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        return view('pages.administrator.parameter_setting.editor', $data);
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
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $parameter_setting = new ParameterSetting;
            $parameter_setting->name = $request['name'];
            $parameter_setting->int_value = $request['int_value'];
            $parameter_setting->double_value = $request['double_value'];
            $parameter_setting->string_value = $request['string_value'];
            $parameter_setting->text_value = $request['text_value'];
            $parameter_setting->created_by = Auth::user()->name;
            if($parameter_setting->save()){
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
        if(Auth::user()->type == 'employee'){
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['parameter_setting'] = ParameterSetting::findOrFail($id);
        return view('pages.administrator.parameter_setting.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->type == 'employee'){
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url.'/'.$id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['parameter_setting'] = ParameterSetting::findOrFail($id);
        return view('pages.administrator.parameter_setting.editor', $data);
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
        $parameter_setting = ParameterSetting::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $parameter_setting->name = $request['name'];
            $parameter_setting->int_value = $request['int_value'];
            $parameter_setting->double_value = $request['double_value'];
            $parameter_setting->string_value = $request['string_value'];
            $parameter_setting->text_value = $request['text_value'];
            $parameter_setting->updated_by = Auth::user()->name;
            if($parameter_setting->save()){
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
        $parameter_setting = ParameterSetting::findOrFail($id);
        if($parameter_setting->delete()){
            \Session::flash('success', 'You are success in deleting your data');
        }else{
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables(){
        $parameter_settings = ParameterSetting::get();

        return DataTables::of($parameter_settings)->make(true);
    }

    public static function getParameter($value){
        return ParameterSetting::where('name',$value)->first();
    }
}
