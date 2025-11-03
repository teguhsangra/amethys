<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\User;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class EmployeeController extends Controller
{
    private $url = 'employee';
    private $form_id = 'employee_form';
    private $table_name = 'employees';
    private $prefix_name = 'EMP';
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
        return view('pages.master.employee.index', $data);
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
        $data['employees'] = Employee::get();
        return view('pages.master.employee.editor', $data);
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
            'code' => 'required|unique:employees',
            'email' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/create')
                        ->withErrors($validator)
                        ->withInput();
        }else{

            DB::beginTransaction();
            $user = new User;
            $user->email = $request['email'];
            $user->name = $request['name'];
            $user->type = "employee";
            $user->password = Hash::make($request['password']);
            $user->created_by = Auth::user()->name;
            if($user->save()){
                $employee = new Employee;
                $employee->user_id = $user->id;
                $employee->parent_id = $request['parent_id'];
                $employee->code = $request['code'];
                $employee->name = $request['name'];
                $employee->email = $request['email'];
                $employee->phone = $request['phone'];
                $employee->role = $request['role'];
                $employee->department = $request['department'];
                $employee->created_by = Auth::user()->name;
                if($employee->save()){
                    DB::commit();
                    \Session::flash('success', 'You are success in inputing your data');
                }else{
                    DB::rollBack();
                    \Session::flash('error', 'You are failed in inputing your data !!!');
                }
            }else{
                DB::rollBack();
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
        $data['employee'] = Employee::findOrFail($id);
        return view('pages.master.employee.detail', $data);
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
        $data['employee'] = Employee::findOrFail($id);
        $data['employees'] = Employee::where('id', '!=', $id)->get();
        return view('pages.master.employee.editor', $data);
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
        $employee = Employee::findOrFail($id);
        $user = $employee->user;
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:employees,code,'.$employee->id,
            'email' => 'required|unique:users,email,'.$user->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $user->email = $request['email'];
            $user->name = $request['name'];
            $user->updated_by = Auth::user()->name;
            if($user->save()){
                $employee->parent_id = $request['parent_id'];
                $employee->code = $request['code'];
                $employee->name = $request['name'];
                $employee->email = $request['email'];
                $employee->phone = $request['phone'];
                $employee->role = $request['role'];
                $employee->department = $request['department'];
                $employee->updated_by = Auth::user()->name;
                if($employee->save()){
                    DB::commit();
                    \Session::flash('success', 'You are success in inputing your data');
                }else{
                    DB::rollBack();
                    \Session::flash('error', 'You are failed in inputing your data !!!');
                }
            }else{
                DB::rollBack();
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
        if($a_g_and_module == null){
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $employee = Employee::findOrFail($id);
        if($employee->delete()){
            $user = $employee->user;
            $user->delete();
            \Session::flash('success', 'You are success in deleting your data');
        }else{
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables(){
        $employees = Employee::leftJoin('users','users.id','employees.parent_id')
            ->select('employees.*','users.name as ac_name')
            ->get();

        return DataTables::of($employees)->make(true);
    }
}
