<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ParameterSetting;
use App\Models\Furniture;
use App\Models\FurniturePhoto;
use DataTables;
use Validator;
use Redirect;
use Image;
use File;
use Auth;
use DB;

class FurnitureController extends Controller
{
    private $url = 'furniture';
    private $form_id = 'furniture_form';
    private $table_name = 'furniture';
    private $prefix_name = 'Fur';
    private $destinationPath = '/uploads/furniture/';
    protected $main_path;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $parameter_of_main_path = ParameterSetting::where('name', 'main_path')->first();
        $this->main_path = $parameter_of_main_path->string_value;
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
        return view('pages.master.furniture.index', $data);
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
        return view('pages.master.furniture.editor', $data);
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
            'code' => 'required|unique:furniture',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $furniture = new Furniture;
            $furniture->code = $request['code'];
            $furniture->name = $request['name'];
            $furniture->created_by = Auth::user()->name;
            if ($furniture->save()) {
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
        $data['furniture'] = Furniture::findOrFail($id);
        return view('pages.master.furniture.detail', $data);
    }

    public function get_by_id($id)
    {
        return Furniture::findOrFail($id);
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
        $data['furniture'] = Furniture::findOrFail($id);
        return view('pages.master.furniture.editor', $data);
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
        $furniture = Furniture::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:furniture,code,' . $furniture->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $furniture->code = $request['code'];
            $furniture->name = $request['name'];
            $furniture->updated_by = Auth::user()->name;
            if ($furniture->save()) {
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
        $furniture = Furniture::findOrFail($id);
        if ($furniture->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function photo($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/photo/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Upload';
        $data['furniture'] = Furniture::findOrFail($id);
        return view('pages.master.furniture.photo', $data);
    }

    public function addPhoto(Request $request, $id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/photo/' . $id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $furniture = Furniture::findOrFail($id);
            $furniture_photo = new FurniturePhoto;
            $furniture_photo->furniture_id = $id;

            $file = $request->file('photo');
            if ($request->hasFile('photo')) {
                $photoName = time() . '.' . $file->getClientOriginalExtension();

                if ($this->main_path == "local") {
                    $path = public_path($this->destinationPath);
                } else {
                    $path = $this->main_path . $this->destinationPath;
                }
                HomeController::check_exist_folder($path);
                $path = $path . $photoName;

                if ($file->getSize() > 1000000) {
                    Image::make($file->getRealPath())->resize(1024, 1024, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path);
                } else {
                    Image::make($file->getRealPath())->save($path);
                }
                $furniture_photo->photo = $this->destinationPath . '' . $photoName;

                if (sizeof(FurniturePhoto::where('furniture_id', $id)->get()) == 0) {
                    $furniture_photo->default = "Y";
                    $furniture->default_photo = $furniture_photo->photo;
                    $furniture->save();
                }
            }

            if ($furniture_photo->save()) {
                \Session::flash('success', 'You are success in inputing your data');
            } else {
                \Session::flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url . '/photo/' . $id);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $furniture_photo = FurniturePhoto::findOrFail($id);
        $furniture = $furniture_photo->furniture;
        if (sizeof(FurniturePhoto::where('furniture_id', $furniture->id)->get()) > 1) {
            if ($furniture_photo->default == "Y" && $request['default_status'] == "N") {
                $other_furniture_photo = FurniturePhoto::where('id', '!=', $id)->first();
                $other_furniture_photo->default = "Y";
                $other_furniture_photo->save();
            }
            if ($furniture_photo->default == "N" && $request['default_status'] == "Y") {
                $other_furniture_photo = FurniturePhoto::where('furniture_id', $furniture->id)->where('default', 'Y')->first();
                $other_furniture_photo->default = "N";
                $other_furniture_photo->save();
            }

            $furniture_photo->default = $request['default_status'];
            $furniture_photo->save();

            $default_furniture_photo = FurniturePhoto::where('furniture_id', $furniture->id)->where('default', 'Y')->first();
            $furniture->default_photo = $default_furniture_photo->photo;
            $furniture->save();
        }

        return "true";
    }

    public function deletePhoto($id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $furniture_photo = FurniturePhoto::findOrFail($id);
        $furniture = $furniture_photo->furniture;
        if ($furniture_photo->delete()) {
            if ($this->main_path == "local") {
                File::Delete(public_path($furniture_photo->photo));
            } else {
                File::Delete($this->main_path . $furniture_photo->photo);
            }
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url . '/photo/' . $furniture_photo->id);
    }

    public function datatables()
    {
        $furniture = Furniture::get();

        return DataTables::of($furniture)->make(true);
    }

    public function datatables_photo($id)
    {
        $furniture_photos = FurniturePhoto::where('furniture_id', $id)->get();

        return DataTables::of($furniture_photos)->make(true);
    }
}
