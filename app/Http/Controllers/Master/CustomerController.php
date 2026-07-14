<?php

namespace App\Http\Controllers\Master;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\CustomerFile;
use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use App\Models\NatureOfBusiness;
use App\Models\ParameterSetting;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    private $url = 'customer';
    private $form_id = 'customer_form';
    private $table_name = 'customers';
    private $prefix_name = 'Cust';
    private $destinationPath = '/uploads/customer/';
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
    public function index(Request $request)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        return view('pages.master.customer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);
        $data['contacts'] = Contact::get();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        return view('pages.master.customer.editor', $data);
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
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:customers',
            'name' => 'required',
            'nature_of_business' => 'string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $customer = new Customer;
            $customer->nature_of_business_id = $request['nature_of_business_id'];
            $customer->nature_of_business = $request['nature_of_business'];
            $customer->code = $request['code'];
            $customer->customer_type = $request['customer_type'];
            $customer->name = $request['name'];
            $customer->brand_name = $request['brand_name'];
            $customer->email = $request['email'];
            $customer->phone = $request['phone'];
            $customer->mobile_phone = $request['mobile_phone'];
            $customer->fax = $request['fax'];
            $customer->address = $request['address'];
            $customer->country = $request['country'];
            $customer->city = $request['city'];
            $customer->zipcode = $request['zipcode'];
            $customer->tax_number = $request['tax_number'];
            $customer->virtual_account_no = $request['virtual_account_no'];
            $customer->virtual_account_bank = $request['virtual_account_bank'];
            $customer->created_by = Auth::user()->name;
            if ($customer->save()) {
                $request->session()->flash('success', 'You are success in inputing your data');
            } else {
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
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
    public function show(Request $request, $id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['customer'] = Customer::findOrFail($id);
        return view('pages.master.customer.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['customer'] = Customer::findOrFail($id);
        $data['contacts'] = Contact::get();
        $data['nature_of_businesses'] = NatureOfBusiness::get();
        return view('pages.master.customer.editor', $data);
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
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $customer = Customer::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $customer->nature_of_business_id = $request['nature_of_business_id'];
            $customer->nature_of_business = $request['nature_of_business'];
            $customer->code = $request['code'];
            $customer->customer_type = $request['customer_type'];
            $customer->name = $request['name'];
            $customer->brand_name = $request['brand_name'];
            $customer->email = $request['email'];
            $customer->phone = $request['phone'];
            $customer->mobile_phone = $request['mobile_phone'];
            $customer->fax = $request['fax'];
            $customer->address = $request['address'];
            $customer->country = $request['country'];
            $customer->city = $request['city'];
            $customer->zipcode = $request['zipcode'];
            $customer->tax_number = $request['tax_number'];
            $customer->virtual_account_no = $request['virtual_account_no'];
            $customer->virtual_account_bank = $request['virtual_account_bank'];
            $customer->updated_by = Auth::user()->name;
            if ($customer->save()) {
                $request->session()->flash('success', 'You are success in updating your data');
            } else {
                $request->session()->flash('error', 'You are failed in updating your data !!!');
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
    public function destroy(Request $request, $id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $customer = Customer::findOrFail($id);
        if ($customer->delete()) {
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function addCustomerContact($customer_id, $contact_id)
    {
        $check_customer_contact = DB::table('customer_and_contact')
            ->where('customer_id', $customer_id)
            ->get();

        $customer = Customer::findOrFail($customer_id);

        if (sizeof($check_customer_contact) == 0) {
            $customer->contact()->attach($contact_id, ['default_status' => 'Y']);
        } else {
            $customer->contact()->attach($contact_id);
        }

        return "true";
    }

    public function editCustomerContact(Request $request, $customer_id, $contact_id)
    {
        $customer_and_contact = DB::table('customer_and_contact')
            ->where('customer_id', $customer_id)
            ->where('contact_id', $contact_id)
            ->first();

        DB::table('customer_and_contact')
            ->where('customer_id', $customer_id)
            ->where('contact_id', $contact_id)
            ->update(['default_status' => $request['default_status'], 'position' => $request['position'], 'department' => $request['department']]);


        if ($customer_and_contact->default_status == "Y" && $request['default_status'] == "N") {
            $new_default_status = DB::table('customer_and_contact')
                ->where('customer_id', $customer_id)
                ->where('contact_id', '!=', $contact_id)
                ->first();

            if ($new_default_status != null) {
                $new_contact_id = $new_default_status->contact_id;

                DB::table('customer_and_contact')
                    ->where('customer_id', $customer_id)
                    ->where('contact_id', $new_contact_id)
                    ->update(['default_status' => 'Y']);
            }
        }
        return "true";
    }

    public function deleteCustomerContact($customer_id, $contact_id)
    {
        $customer_and_contact = DB::table('customer_and_contact')
            ->where('customer_id', $customer_id)
            ->where('contact_id', $contact_id)
            ->first();

        DB::table('customer_and_contact')
            ->where('customer_id', $customer_id)
            ->where('contact_id', $contact_id)
            ->delete();

        if ($customer_and_contact->default_status == "Y") {
            $new_default_status = DB::table('customer_and_contact')
                ->where('customer_id', $customer_id)
                ->where('contact_id', '!=', $contact_id)
                ->first();

            if ($new_default_status != null) {
                $new_contact_id = $new_default_status->contact_id;

                DB::table('customer_and_contact')
                    ->where('customer_id', $customer_id)
                    ->where('contact_id', $new_contact_id)
                    ->update(['default_status' => 'Y']);
            }
        }
        return "true";
    }

    public function file(Request $request, $id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/file/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Upload';
        $data['customer'] = Customer::findOrFail($id);
        return view('pages.master.customer.file', $data);
    }

    public function addFile(Request $request, $id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/file/' . $id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $customer = Customer::findOrFail($id);
            $customer_file = new CustomerFile;
            $customer_file->customer_id = $id;
            $customer_file->name = $request['name'];

            $file = $request->file('file');
            if ($request->hasFile('file')) {
                if ($request->file('file')->getSize() > 2000000) {
                    $request->session()->flash('error', "You can't upload file above 2 MB !!!");
                    return Redirect::to($this->url . '/file/' . $id);
                }
                if ($this->main_path == "local") {
                    $path = public_path($this->destinationPath);
                } else {
                    $path = $this->main_path . $this->destinationPath;
                }

                HomeController::check_exist_folder($path);

                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move($path, $filename);
                $customer_file->file = $this->destinationPath . $filename;
            }

            if ($customer_file->save()) {
                $request->session()->flash('success', 'You are success in inputing your data');
            } else {
                $request->session()->flash('error', 'You are failed in inputing your data !!!');
            }
            return Redirect::to($this->url . '/file/' . $id);
        }
    }

    public function deleteFile(Request $request, $id)
    {
        $a_g_and_module = HomeController::getAccess($this->url);
        if ($a_g_and_module == null) {
            $request->session()->flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }
        $customer_file = CustomerFile::findOrFail($id);
        $customer = $customer_file->customer;
        if ($customer_file->delete()) {
            if ($this->main_path == "local") {
                $path = public_path($customer_file->file);
            } else {
                $path = $this->main_path . $customer_file->file;
            }
            File::Delete($path);
            $request->session()->flash('success', 'You are success in deleting your data');
        } else {
            $request->session()->flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url . '/file/' . $customer->id);
    }

    public function datatables(Request $request)
    {
        $customers = Customer::get();

        return DataTables::of($customers)->make(true);
    }

    public function get_by_id($customer_id)
    {
        return Customer::findOrFail($customer_id);
    }

    public function datatables_not_assign_contact($customer_id)
    {
        $contact_id = array();
        $customer_contact = DB::table('customer_and_contact')->where('customer_id', $customer_id)->get();

        foreach ($customer_contact as $detail) {
            array_push($contact_id, $detail->contact_id);
        }

        $contacts = Contact::whereNotIn('id', $contact_id)->get();

        return DataTables::of($contacts)->make(true);
    }

    public function datatables_assign_contact($customer_id)
    {
        $customer_contact = DB::table('customer_and_contact')
            ->join('contacts', 'contacts.id', 'customer_and_contact.contact_id')
            ->select('customer_and_contact.*', 'contacts.name as contact_name')
            ->where('customer_id', $customer_id)
            ->get();

        return DataTables::of($customer_contact)->make(true);
    }

    public function datatables_file($customer_id)
    {
        $customer_files = CustomerFile::where('customer_id', $customer_id)->get();

        return DataTables::of($customer_files)->make(true);
    }

    public static function create_from_transaction($request)
    {
        if ($request['customer_status'] == 'N') {
            $customer = new Customer;
            $customer->code = HomeController::getMasterCode('customers', 'Cust');
            $customer->nature_of_business_id = $request['nature_of_business_id'];
            $customer->nature_of_business = $request['nature_of_business'];
            $customer->name = $request['customer_name'];
            $customer->customer_type = $request['customer_type'];
            $customer->email = $request['customer_email'];
            $customer->fax = $request['customer_fax'];
            $customer->phone = $request['customer_phone'];
            $customer->mobile_phone = $request['customer_mobile_phone'];
            $customer->address = $request['customer_address'];
            $customer->country = $request['customer_country'];
            $customer->city = $request['customer_city'];
            $customer->zipcode = $request['customer_zipcode'];
            $customer->tax_number = $request['customer_tax_number'];
            $customer->created_by = Auth::user()->name;
            if ($customer->save()) {
                $customer_id = $customer->id;
            } else {
                $customer_id = null;
            }
        } else {
            $customer_id = $request['customer_id'];
        }

        return $customer_id;
    }

    public function exportToExcel(Request $request)
    {
        $data['customers'] = Customer::get();

        return Excel::download(new CustomerExport($data), 'Customer_' . date('Y_m_d_H_i_s') . '.xlsx');
    }
}
