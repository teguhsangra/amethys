<?php

namespace App\Http\Controllers\Master;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ParameterSetting;
use App\Models\MarketingMaterial;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class MarketingMaterialController extends Controller
{

    private $url = 'marketing_material';
    private $form_id = 'marketing_material_form';
    private $table_name = 'marketing_materials';
    private $prefix_name = 'Mm';
    private $destinationPath = '/uploads/marketing_material/';
    protected $main_path;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $parameter_of_main_path = ParameterSetting::where('name', 'main_path')->first();
        $this->main_path = $parameter_of_main_path->string_value;
    }

    public function index()
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        return view('pages.master.marketing_material.index', $data);
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
        return view('pages.master.marketing_material.editor', $data);
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
            'code' => 'required|unique:marketing_materials',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $marketing_material = new MarketingMaterial;
            $marketing_material->code = $request['code'];
            $marketing_material->name = $request['name'];
            $marketing_material->desc = $request['desc'];
            $marketing_material->created_by = Auth::user()->name;
            $file = $request->file('file');
            if ($request->hasFile('file')) {
                if ($request->file('file')->getSize() > 2000000) {
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url . '/create');
                }
                if ($this->main_path == "local") {
                    $path = public_path($this->destinationPath);
                } else {
                    $path = public_path($this->main_path . $this->destinationPath);
                }

                HomeController::check_exist_folder($path);

                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_marketing_material_.' . $extension;
                $file->move($path, $filename);

                $marketing_material->file_type = $extension;
                $marketing_material->file_path = $this->destinationPath . $filename;
            }
            if ($marketing_material->save()) {
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
        $data['marketing_material'] = MarketingMaterial::findOrFail($id);
        return view('pages.master.marketing_material.detail', $data);
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
        $data['marketing_material'] = MarketingMaterial::findOrFail($id);
        return view('pages.master.marketing_material.editor', $data);
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
        $marketing_material = MarketingMaterial::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:marketing_materials,code,' . $marketing_material->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $marketing_material->code = $request['code'];
            $marketing_material->name = $request['name'];
            $marketing_material->desc = $request['desc'];
            $marketing_material->updated_by = Auth::user()->name;
            $file = $request->file('file');
            if ($request->hasFile('file')) {
                $delete_path = null;
                if ($request->file('file')->getSize() > 2000000) {
                    \Session::flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url . '/create');
                }
                if ($this->main_path == "local") {
                    $path = public_path($this->destinationPath);
                    if ($marketing_material->file_path != null) {
                        $delete_path = public_path($marketing_material->file_path);
                    }
                } else {
                    $path = public_path($this->destinationPath);
                    if ($marketing_material->file_path != null) {
                        $delete_path = $this->main_path . $marketing_material->file_path;
                    }
                }

                HomeController::check_exist_folder($path);

                if ($delete_path != null) {
                    \File::Delete($delete_path);
                }

                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_marketing_material_.' . $extension;
                $file->move($path, $filename);
                $marketing_material->file_type = $extension;
                $marketing_material->file_path = $this->destinationPath . $filename;
            }
            if ($marketing_material->save()) {
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
        $marketing_material = MarketingMaterial::findOrFail($id);
        if ($marketing_material->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function datatables()
    {
        $marketing_material = MarketingMaterial::all();
        return DataTables::of($marketing_material)->make(true);
    }
}
