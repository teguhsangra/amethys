<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class ModuleController extends Controller
{
    private $url = 'module';
    private $form_id = 'module_form';
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
        return view('pages.administrator.module.index', $data);
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
        $data['modules'] = Module::get();
        return view('pages.administrator.module.editor', $data);
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
            'icon' => 'required',
            'link' => 'required|unique:modules',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $module = new Module;
            $module->parent_id = $request['parent_id'];
            $module->name = $request['name'];
            $module->icon = $request['icon'];
            $module->link = $request['link'];
            $module->created_by = Auth::user()->name;
            if($module->save()){
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
        $data['module'] = Module::findOrFail($id);
        return view('pages.administrator.module.detail', $data);
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
        $data['module'] = Module::findOrFail($id);
        $data['modules'] = Module::get();
        return view('pages.administrator.module.editor', $data);
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
        $module = Module::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
            'link' => 'required|unique:modules,link,'.$module->id,
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $module->parent_id = $request['parent_id'];
            $module->name = $request['name'];
            $module->icon = $request['icon'];
            $module->link = $request['link'];
            $module->updated_by = Auth::user()->name;
            if($module->save()){
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
        $module = Module::findOrFail($id);
        if($module->delete()){
            \Session::flash('success', 'You are success in deleting your data');
        }else{
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables()
    {
        $modules = Module::leftJoin('modules as pm','pm.id','modules.parent_id')
            ->select('modules.*','pm.name as parent_name')
            ->get();

        return DataTables::of($modules)->make(true);
    }
}
