<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Product;
use App\Models\Room;
use App\Models\Package;
use App\Models\Location;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class PackageController extends Controller
{
    private $url = 'package';
    private $form_id = 'package_form';
    private $table_name = 'packages';
    private $prefix_name = 'Pack';
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
        return view('pages.master.package.index', $data);
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
        $location_id = \Request::get('location_id');
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);
        $data['locations'] = Location::get();
        $data['products'] = Product::get();
        if (!empty($location_id)) {
            $data['rooms'] = Room::where('location_id', $location_id)->get();
        } else {
            $data['rooms'] = array();
        }
        return view('pages.master.package.editor', $data);
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
            'code' => 'required|unique:packages',
            'name' => 'required',
            'price_type' => 'required',
            'total_term' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $package = new Package;
            $package->location_id = $request['location_id'];
            $package->code = $request['code'];
            $package->name = $request['name'];
            $package->price_type = $request['price_type'];
            $package->price = $request['price'];
            $package->total_term = $request['total_term'];
            $package->desc = $request['desc'];
            $package->main_status = $request['main_status'];
            $package->quantity_status = $request['quantity_status'];
            $package->has_service_charge = $request['has_service_charge'];
            $package->created_by = Auth::user()->name;
            if ($package->save()) {
                if (!empty($request['room_id'])) {
                    for ($i = 0; $i < sizeof($request['room_id']); $i++) {
                        $package->room()->attach($request['room_id'][$i]);
                    }
                }
                if (!empty($request['product_id'])) {
                    for ($i = 0; $i < sizeof($request['product_id']); $i++) {
                        $package->product()->attach($request['product_id'][$i]);
                    }
                }
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
        $data['package'] = Package::findOrFail($id);
        return view('pages.master.package.detail', $data);
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
        $location_id = \Request::get('location_id');
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['package'] = Package::findOrFail($id);
        $data['products'] = Product::get();
        $data['locations'] = Location::get();
        if ($data['package']->location_id != null) {
            $data['rooms'] = Room::where('location_id', $data['package']->location_id)->get();
        } else {
            $data['rooms'] = array();
        }
        if (!empty($location_id)) {
            $data['rooms'] = Room::where('location_id', $location_id)->get();
        }
        return view('pages.master.package.editor', $data);
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
        $package = Package::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:packages,code,' . $package->id,
            'name' => 'required',
            'price_type' => 'required',
            'total_term' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $package->location_id = $request['location_id'];
            $package->code = $request['code'];
            $package->name = $request['name'];
            $package->price_type = $request['price_type'];
            $package->price = $request['price'];
            $package->total_term = $request['total_term'];
            $package->desc = $request['desc'];
            $package->main_status = $request['main_status'];
            $package->quantity_status = $request['quantity_status'];
            $package->has_service_charge = $request['has_service_charge'];
            $package->updated_by = Auth::user()->name;
            if ($package->save()) {
                DB::table('package_and_room')->where('package_id', $id)->delete();
                DB::table('package_and_product')->where('package_id', $id)->delete();
                if (!empty($request['room_id'])) {
                    for ($i = 0; $i < sizeof($request['room_id']); $i++) {
                        $package->room()->attach($request['room_id'][$i]);
                    }
                }
                if (!empty($request['product_id'])) {
                    for ($i = 0; $i < sizeof($request['product_id']); $i++) {
                        $package->product()->attach($request['product_id'][$i]);
                    }
                }
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
        $package = Package::findOrFail($id);
        if ($package->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function get_by_id($id)
    {
        return Package::findOrFail($id);
    }

    public function get_by_location_id($location_id)
    {
        return Package::where('location_id', $location_id)->orWhere('location_id', null)->get();
    }

    public function datatables()
    {
        $packages = Package::get();

        return DataTables::of($packages)
            ->editColumn('price', function ($data) {
                return number_format($data->price, 0, ',', '.');
            })
            ->make(true);
    }

    public function datatables_by_location($location_id)
    {
        $packages = Package::where('location_id', $location_id)->get();

        return DataTables::of($packages)
            ->editColumn('price', function ($data) {
                return number_format($data->price, 0, ',', '.');
            })
            ->make(true);
    }
}
