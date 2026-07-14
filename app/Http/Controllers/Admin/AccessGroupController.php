<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\AccessGroup;
use App\Models\Module;
use App\User;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class AccessGroupController extends Controller
{
    private $url = 'access_group';
    private $form_id = 'access_group_form';
    private $table_name = 'access_groups';
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
        if (Auth::user()->type == 'employee') {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        return view('pages.administrator.access_group.index', $data);
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
        return view('pages.administrator.access_group.editor', $data);
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
            'code' => 'required|unique:access_groups',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $access_group = new AccessGroup;
            $access_group->code = $request['code'];
            $access_group->name = $request['name'];
            $access_group->created_by = Auth::user()->name;
            if ($access_group->save()) {
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
        $data['access_group'] = AccessGroup::findOrFail($id);
        return view('pages.administrator.access_group.detail', $data);
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
        $data['access_group'] = AccessGroup::findOrFail($id);
        return view('pages.administrator.access_group.editor', $data);
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
        $access_group = AccessGroup::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:access_groups,code,' . $access_group->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $access_group->code = $request['code'];
            $access_group->name = $request['name'];
            $access_group->updated_by = Auth::user()->name;
            if ($access_group->save()) {
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
        $access_group = AccessGroup::findOrFail($id);
        if ($access_group->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function manage(Request $request, $id)
    {
        $data['id'] = $id;
        $data['access_group'] = AccessGroup::findOrFail($id);
        return view('pages.administrator.access_group.manage', $data);
    }

    public function assignModule(Request $request, $id)
    {
        $access_group = AccessGroup::findOrFail($id);
        $module_id = $request['module_id'];

        $nextInput = true;
        while ($nextInput) {
            $check = $access_group->module()->where('module_id', $module_id)->get();

            if (sizeof($check) == 0)
                $access_group->module()->attach($module_id, ['read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1, 'showDataByStructure' => 1]);
            $module = Module::findOrFail($module_id);

            if ($module->parent_id != null) {
                $module_id = $module->parent_id;
            } else {
                $nextInput = false;
            }
        }

        \Session::flash('success', 'You are success assign module');
        return Redirect::to($this->url . '/manage/' . $id);
    }

    public function editAGM(Request $request, $id)
    {
        $access_group = AccessGroup::findOrFail($id);

        if (!isset($request['read']))
            $read = 0;
        else
            $read = 1;
        if (!isset($request['create']))
            $create = 0;
        else
            $create = 1;
        if (!isset($request['update']))
            $update = 0;
        else
            $update = 1;
        if (!isset($request['delete']))
            $delete = 0;
        else
            $delete = 1;
        if (!isset($request['isExec']))
            $isExec = 0;
        else
            $isExec = 1;
        if (!isset($request['showDataByStructure']))
            $showDataByStructure = 0;
        else
            $showDataByStructure = 1;

        $access_group->module()->detach($request['module_id']);
        $access_group->module()->attach($request['module_id'], ['read' => $read, 'create' => $create, 'update' => $update, 'delete' => $delete, 'isExec' => $isExec, 'showDataByStructure' => $showDataByStructure]);

        \Session::flash('success', 'You are success edit selected module');
        return Redirect::to($this->url . '/manage/' . $id);
    }

    public function unassignModule(Request $request, $id)
    {
        $access_group = AccessGroup::findOrFail($id);
        $module_id = $request['module_id'];

        $modul_ids = array();
        $i = 0;
        $module_id_of_list = $access_group->module()->select('module_id')->get();

        foreach ($module_id_of_list as $listModuleId) {
            $modul_ids[$i] = $listModuleId->module_id;
            $i++;
        }

        $modules = DB::table('modules')->select('*')->where('parent_id', $module_id)->whereIn('id', $modul_ids)->get();
        if (!empty($modules->toArray())) {
            foreach ($modules as $modules) {
                $access_group->module()->detach($modules->id);
            }
        }

        $access_group->module()->detach($module_id);

        \Session::flash('success', 'You are success unassign selected module');
        return Redirect::to($this->url . '/manage/' . $id);
    }

    public function assignUser(Request $request, $id)
    {
        $user = User::findOrFail($request['user_id']);
        $user->access_group_id = $id;

        if ($user->save()) {
            \Session::flash('success', 'You are success in assign user');
        } else {
            \Session::flash('error', 'You are failed in assign user !!!');
        }
        return Redirect::to($this->url . '/manage/' . $id);
    }

    public function unAssignUser(Request $request, $id)
    {
        $user = User::findOrFail($request['user_id']);
        $user->access_group_id = null;

        if ($user->save()) {
            \Session::flash('success', 'You are success in unassign user');
        } else {
            \Session::flash('error', 'You are failed in unassign user !!!');
        }
        return Redirect::to($this->url . '/manage/' . $id);
    }

    public function datatables()
    {
        $access_groups = AccessGroup::get();

        return DataTables::of($access_groups)->make(true);
    }

    public function datatables_unassigned_user($id)
    {
        $query = DB::table('users')->where('access_group_id', null);

        return DataTables::queryBuilder($query)->make(true);
    }

    public function datatables_assigned_user($id)
    {
        $query = DB::table('users')->where('access_group_id', $id);

        return DataTables::queryBuilder($query)->make(true);
    }

    public function datatables_unassigned_module($id)
    {
        $results = DB::select(DB::raw("SELECT module_id FROM a_g_and_module WHERE access_group_id = '$id'"));

        $module_ids = array();
        foreach ($results as $no => $result) {
            $module_ids[$no] = $result->module_id;
        }

        $query = DB::table('modules')->whereNotIn('id', $module_ids);

        return DataTables::queryBuilder($query)->make(true);
    }

    public function datatables_assigned_module($id)
    {
        $modules = DB::table('modules')->select('modules.id', 'modules.name', 'a_g_and_module.*')
            ->join('a_g_and_module', 'modules.id', 'a_g_and_module.module_id')
            ->where('a_g_and_module.access_group_id', $id);

        return DataTables::of($modules)->make(true);
    }
}

