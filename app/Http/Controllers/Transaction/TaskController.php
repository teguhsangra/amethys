<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotificationController;
use App\Models\Location;
use App\Models\Employee;
use App\Models\Task;
use App\Models\TaskSubject;
use App\Models\Ticketing;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class TaskController extends Controller
{
    private $url = 'task';
    private $form_id = 'tasks_form';
    private $table_name = 'tasks';
    private $prefix_name = 'TASK';
    private $ids = array();
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        return view('pages.transaction.task.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['previous'] = Task::get();
        $data['task_subject'] = TaskSubject::get();
        $data['ticketing'] = Ticketing::get();
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);
        $data['employee'] = Employee::get();
        return view('pages.transaction.task.editor', $data);
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
            'employee_id' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            DB::beginTransaction();
            $task = new Task;
            $task->user_id = Auth::user()->id;
            $task->location_id = $request['location_id'];
            $task->employee_id =  $request['employee_id'];
            $task->previous_id = $request['previous_id'];
            $task->ticketing_id = $request['ticketing_id'];
            $task->task_subject_id = $request['task_subject_id'];
            $task->code = HomeController::getMasterCode($this->table_name, $this->prefix_name);
            $task->is_escalated = "N";
            $task->is_closed = "N";
            $task->subject = $request['subject'];
            $task->remarks = $request['remarks'];
            $task->estimated_done_at =  date('Y-m-d', strtotime($request['estimated_done_at']));
            if ($task->save()) {
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
                NotificationController::notification('create_task', $task->id);
            } else {
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['task'] = Task::findOrFail($id);
        $data['url'] = $this->url;
        return view('pages.transaction.task.detail', $data);
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $this->get_child_of_this_employee($employee->id);
        $this->ids[sizeof($this->ids)] = $employee->id;
        $active_status_id = array(2);

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['previous'] = Task::get();
        $data['task_subject'] = TaskSubject::get();
        $data['ticketing'] = Ticketing::get();
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);
        $data['employee'] = Employee::get();
        $data['task'] = Task::findOrFail($id);
        return view('pages.transaction.task.editor', $data);
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

        $task = Task::findOrFail($id);
        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
            'employee_id' => 'required',
            'code' => 'required'

        ]);

        if ($validator->fails()) {
            return redirect($this->url . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {


            $task->user_id = Auth::user()->id;
            $task->location_id = $request['location_id'];
            $task->employee_id =  $request['employee_id'];
            $task->previous_id = $request['previous_id'];
            $task->ticketing_id = $request['ticketing_id'];
            $task->task_subject_id = $request['task_subject_id'];
            $task->is_escalated = $request['is_escalated'];

            $task->subject = $request['subject'];
            $task->remarks = $request['remarks'];
            $task->estimated_done_at =  date('Y-m-d', strtotime($request['estimated_done_at']));
            if ($task->is_escalated == "Y") {
                $task->closed_at = date('Y-m-d');
                $task->escalated_at = date('Y-m-d');
                $task->is_closed = "Y";
            } else {
                $task->is_closed = "N";
            }
            if ($task->save()) {
                if ($task->is_escalated == "Y") {
                    $task = new Task;
                    $task->user_id = Auth::user()->id;
                    $task->location_id = $request['location_id'];
                    $task->employee_id =  $request['employee_id'];
                    $task->previous_id = $request['previous_id'];
                    $task->ticketing_id = $request['ticketing_id'];
                    $task->task_subject_id = $request['task_subject_id'];
                    $task->is_escalated = "N";
                    $task->is_closed = "N";
                    $task->subject = $request['subject'];
                    $task->remarks = $request['remarks'];
                    $task->estimated_done_at =  date('Y-m-d', strtotime($request['estimated_done_at']));
                    if (!$task->save()) {
                        DB::rollBack();
                        \Session::flash('error', 'You are failed in inputing your data !!!');
                    }
                }
                DB::commit();
                \Session::flash('success', 'You are success in inputing your data');
                NotificationController::notification('escalated_task', $task->id);
            } else {
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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $task = Task::findOrFail($id);
        $task->is_closed = "Y";
        $task->closed_at = date('Y-m-d');
        if ($task->save()) {
            NotificationController::notification('closed_task', $task->id);
            \Session::flash('success', 'Closed Task = ' . $task->code);
        } else {
            \Session::flash('error', 'Failed');
        }
        return Redirect::to($this->url);
    }

    public function datatables()
    {
        $task = Task::select(
            'tasks.*',
            'locations.name as location',
            'previous.code as previous_code',
            DB::raw('(CASE WHEN tasks.is_closed = "Y" THEN "Closed" ELSE "Open" END) AS status')
        )
            ->leftJoin('tasks as previous', 'previous.id', 'tasks.previous_id')
            ->join('locations', 'locations.id', 'tasks.location_id')
            ->get();

        return DataTables::of($task)
            ->editColumn('subject', function ($data) {
                if ($data->subject == null) {
                    return $data->task_subject->name;
                } else {
                    return $data->subject;
                }
            })
            ->make(true);
    }

    public function get_child_of_this_employee($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);

        $show_data_by_structure = false;

        if ($a_g_and_module != null) {
            if ($a_g_and_module->showDataByStructure == 1) {
                $show_data_by_structure = true;
            }
        }

        if ($show_data_by_structure) {
            $employee = Employee::findOrFail($id);
            if (sizeof($employee->this_child) > 0) {
                foreach ($employee->this_child as $no => $detail) {
                    $this->ids[sizeof($this->ids)] = $detail->id;
                    $this->get_child_of_this_employee($detail->id);
                }
            }
        } else {
            $employees = Employee::where('id', '!=', $id)->get();
            foreach ($employees as $detail) {
                array_push($this->ids, $detail->id);
            }
        }
    }
}
