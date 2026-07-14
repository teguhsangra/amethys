<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Hash;
use App\Models\ParameterSetting;
use App\Models\Employee;
use App\User;
use Validator;
use Redirect;
use File;
use Image;
use Auth;
use DB;

class ProfileController extends Controller
{
    private $url = 'profile';
    private $destinationPath = '/uploads/profile/';
    protected $main_path;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $parameter_of_main_path = ParameterSetting::where('name','main_path')->first();
        $this->main_path = $parameter_of_main_path->string_value;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $data['form_url'] = $this->url.'/'.$employee->id;
        $data['employee'] = $employee;
        return view('pages.profile.index', $data);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $employee = Employee::findOrFail($id);
        $user = $employee->user;

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users,email,'.$user->id,
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect($this->url)
                        ->withErrors($validator)
                        ->withInput();
        }else{
            DB::beginTransaction();
            $employee->name = $request['name'];
            $employee->email = $request['email'];
            $employee->phone = $request['phone'];
            $employee->role = $request['role'];
            $employee->department = $request['department'];
            if($employee->save()){
                $user->bio = $request['bio'];
                $user->email = $request['email'];
                if(!empty($request['password'])){
                    $user->password = Hash::make($request['password']);
                }

                $file = $request->file('photo');
                if ($request->hasFile('photo')) {
                    $photoName = time().'.'.$file->getClientOriginalExtension();

                    if($this->main_path == "local"){
                        $path = public_path($this->destinationPath);
                    }else{
                        $path = $this->main_path.$this->destinationPath;
                    }
                    HomeController::check_exist_folder($path);
                    $path = $path.$photoName;

                    if($file->getSize() > 1000000){
                        Image::make($file->getRealPath())->resize(1024, 1024, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($path);
                    }else{
                        Image::make($file->getRealPath())->save($path);
                    }


                    if($user->photo != null){
                        if($this->main_path == "local"){
                            File::Delete(public_path($photoName));
                        }else{
                            File::Delete($this->main_path. $photoName);
                        }
                    }

                    $user->photo =  $this->destinationPath.''.$photoName;
                }
                $user->save();
                DB::commit();
                \Session::flash('success', 'You are success in updating your data');
            }else{
                DB::rollBack();
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
        //
    }
}
