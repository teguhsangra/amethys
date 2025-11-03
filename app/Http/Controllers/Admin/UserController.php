<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\User;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class UserController extends Controller
{
    private $url = 'user';
    private $form_id = 'user_form';
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
        return view('pages.administrator.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $data['user'] = User::findOrFail($id);
        return view('pages.administrator.user.detail', $data);
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
        $data['user'] = User::findOrFail($id);
        $data['locations'] = Location::get();
        return view('pages.administrator.user.editor', $data);
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
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url.'/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $user->type = $request['type'];
            $user->bio = $request['bio'];
            $user->updated_by = Auth::user()->name;
            if($user->save()){
                DB::table('user_and_location')->where('user_id', $id)->delete();
                for($i=0; $i<sizeof($request['location_id']); $i++){
                    $user->location()->attach($request['location_id'][$i]);
                }
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
        $user = User::findOrFail($id);
        if($user->delete()){
            \Session::flash('success', 'You are success in deleting your data');
        }else{
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function reset_password(Request $request){
        $id = $request['user_id'];
        if(!empty($request['password'])){
            $user = User::findOrFail($id);
            $user->password = bcrypt($request['password']);
            $user->updated_by = Auth::user()->name;
            if($user->save()){
                \Session::flash('success', 'You are success in reseting '.$user['name'].' password');
            }else{
                \Session::flash('error', 'You are failed in reseting '.$user['name'].' password !!!');
            }
        }else{
            \Session::flash('error', 'You have to input new password for reset it !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables()
    {
        $users = User::select('users.*','access_groups.name as ac_name')
            ->leftJoin('access_groups','users.access_group_id','access_groups.id');

        return DataTables::of($users)->make(true);
    }
}
