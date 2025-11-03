<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ParameterSetting;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\ProductCategory;
use App\Models\ProductArea;
use App\Models\ProductAreaDetail;
use App\Models\Area;
use DataTables;
use Validator;
use Redirect;
use Image;
use File;
use Auth;
use DB;

class ProductController extends Controller
{
    private $url = 'product';
    private $form_id = 'product_form';
    private $table_name = 'products';
    private $prefix_name = 'PR';
    private $destinationPath = '/uploads/product/';
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
        return view('pages.master.product.index', $data);
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
        $data['product_categories'] = ProductCategory::get();
        return view('pages.master.product.editor', $data);
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
            'code' => 'required|unique:products',
            'name' => 'required',
            'price' => 'required',
            'price_type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $product = new Product;
            $product->code = $request['code'];
            $product->name = $request['name'];
            $product->has_service_charge = $request['has_service_charge'];
            $product->price_type = $request['price_type'];
            $product->price = $request['price'];
            $product->type = $request['type'];
            $product->main_status = $request['main_status'];
            $product->quantity_status = $request['quantity_status'];
            $product->is_editable_price = $request['is_editable_price'];
            $product->desc = $request['desc'];
            $product->created_by = Auth::user()->name;
            if ($product->save()) {
                if (!empty($request['product_category_id'])) {
                    for ($i = 0; $i < sizeof($request['product_category_id']); $i++) {
                        $product->product_category()->attach($request['product_category_id'][$i]);
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
        $data['product'] = Product::findOrFail($id);
        return view('pages.master.product.detail', $data);
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
        $data['product'] = Product::findOrFail($id);
        $data['product_categories'] = ProductCategory::get();
        return view('pages.master.product.editor', $data);
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
        $product = Product::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required',
            'price' => 'required',
            'price_type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $product->code = $request['code'];
            $product->name = $request['name'];
            $product->has_service_charge = $request['has_service_charge'];
            $product->price_type = $request['price_type'];
            $product->price = $request['price'];
            $product->type = $request['type'];
            $product->main_status = $request['main_status'];
            $product->quantity_status = $request['quantity_status'];
            $product->is_editable_price = $request['is_editable_price'];
            $product->desc = $request['desc'];
            $product->updated_by = Auth::user()->name;
            if ($product->save()) {
                DB::table('p_c_and_product')->where('product_id', $id)->delete();
                if (!empty($request['product_category_id'])) {
                    for ($i = 0; $i < sizeof($request['product_category_id']); $i++) {
                        $product->product_category()->attach($request['product_category_id'][$i]);
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
        $product = Product::findOrFail($id);
        if ($product->delete()) {
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
        $data['product'] = Product::findOrFail($id);
        return view('pages.master.product.photo', $data);
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
            $product = Product::findOrFail($id);
            $product_photo = new ProductPhoto;
            $product_photo->product_id = $id;

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
                $product_photo->photo = $this->destinationPath . '' . $photoName;

                if (sizeof(ProductPhoto::where('product_id', $id)->get()) == 0) {
                    $product_photo->default = "Y";
                    $product->default_photo = $product_photo->photo;
                    $product->save();
                }
            }

            if ($product_photo->save()) {
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
        $product_photo = ProductPhoto::findOrFail($id);
        $product = $product_photo->product;
        if (sizeof(ProductPhoto::where('product_id', $product->id)->get()) > 1) {
            if ($product_photo->default == "Y" && $request['default_status'] == "N") {
                $other_product_photo = ProductPhoto::where('id', '!=', $id)->first();
                $other_product_photo->default = "Y";
                $other_product_photo->save();
            }
            if ($product_photo->default == "N" && $request['default_status'] == "Y") {
                $other_product_photo = ProductPhoto::where('product_id', $product->id)->where('default', 'Y')->first();
                $other_product_photo->default = "N";
                $other_product_photo->save();
            }

            $product_photo->default = $request['default_status'];
            $product_photo->save();

            $default_product_photo = ProductPhoto::where('product_id', $product->id)->where('default', 'Y')->first();
            $product->default_photo = $default_product_photo->photo;
            $product->save();
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
        $product_photo = ProductPhoto::findOrFail($id);
        $product = $product_photo->product;
        if ($product_photo->delete()) {
            if ($this->main_path == "local") {
                File::Delete(public_path($product_photo->photo));
            } else {
                File::Delete($this->main_path . $product_photo->photo);
            }
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url . '/photo/' . $product->id);
    }

    public function get_by_id($id)
    {
        return Product::findOrFail($id);
    }

    public function get_by_location_id(Request $request, $location_id)
    {
        $main_status = $request['main_status'];
        if (!empty($main_status)) {
            $products = Product::where('main_status', $main_status)
                ->where(function ($q) use ($location_id) {
                    $q->where('location_id', $location_id)
                        ->orWhere('location_id', null);
                })
                ->get();
        } else {
            $products = Product::where('location_id', $location_id)->orWhere('location_id', null)->get();
        }

        return $products;
    }

    public function datatables()
    {
        $products = Product::get();

        return DataTables::of($products)
            ->editColumn('price', function ($data) {
                return number_format($data->price, 0, ',', '.');
            })
            ->make(true);
    }

    public function datatables_photo($id)
    {
        $product_photos = ProductPhoto::where('product_id', $id)->get();

        return DataTables::of($product_photos)->make(true);
    }
}
