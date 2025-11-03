<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Contact;
use App\Models\Customer;
use App\Exports\ContactExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;
use Carbon\Carbon;

class ContactController extends Controller
{
    private $url = 'contact';
    private $form_id = 'contact_form';
    private $table_name = 'contacts';
    private $prefix_name = 'Co';
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
        return view('pages.master.contact.index', $data);
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
        return view('pages.master.contact.editor', $data);
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
            'code' => 'required|unique:contacts',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $contact = new Contact;
            $contact->code = $request['code'];
            $contact->name = $request['name'];
            $contact->id_number = $request['id_number'];
            $contact->email = $request['email'];
            $contact->phone = $request['phone'];
            $contact->mobile_phone = $request['mobile_phone'];
            $contact->address = $request['address'];
            $contact->birth_date = date('Y-m-d', strtotime($request['birth_date']));
            $contact->created_by = Auth::user()->name;
            if ($contact->save()) {
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
        $data['contact'] = Contact::findOrFail($id);
        return view('pages.master.contact.detail', $data);
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
        $data['contact'] = Contact::findOrFail($id);
        return view('pages.master.contact.editor', $data);
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
        $contact = Contact::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:contacts,code,' . $contact->id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $contact->code = $request['code'];
            $contact->name = $request['name'];
            $contact->id_number = $request['id_number'];
            $contact->email = $request['email'];
            $contact->phone = $request['phone'];
            $contact->mobile_phone = $request['mobile_phone'];
            $contact->address = $request['address'];
            $contact->birth_date = date('Y-m-d', strtotime($request['birth_date']));
            $contact->updated_by = Auth::user()->name;
            if ($contact->save()) {
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
        $contact = Contact::findOrFail($id);
        if ($contact->delete()) {
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url);
    }

    public function get_by_customer($customer_id)
    {
        $in_cust = Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
            ->select('contacts.*')
            ->where('customer_id', $customer_id)
            ->get();

        return $in_cust;
    }

    public function get_by_sales_activity($sales_activity_id)
    {
        return Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
            ->join('sales_activities', 'sales_activities.customer_id', 'customer_and_contact.customer_id')
            ->select('contacts.*')
            ->where('sales_activities.id', $sales_activity_id)
            ->get();
    }

    public function get_by_prospect($prospect_id)
    {
        return Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
            ->join('prospects', 'prospects.customer_id', 'customer_and_contact.customer_id')
            ->select('contacts.*')
            ->where('prospects.id', $prospect_id)
            ->get();
    }

    public function datatables()
    {
        $contacts = Contact::get();

        return DataTables::of($contacts)->make(true);
    }

    public static function create_from_transaction($request, $customer_id)
    {
        $contact_id = null;
        if ($request['contact_status'] == "same_with_customer") {
            $contact = Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
                ->where('customer_and_contact.customer_id', $customer_id)
                ->where('customer_and_contact.default_status', 'Y')
                ->first();
            if ($contact != null) {
                $contact_id = $contact->id;
            } else {
                $customer = Customer::findOrFail($customer_id);

                $contact = new Contact;
                $contact->code = HomeController::getMasterCode('contacts', 'Co');
                $contact->name = $customer->name;
                $contact->email = $customer->email;
                $contact->phone = $customer->phone;
                if ($contact->save()) {
                    $contact_id = $contact->id;
                    $customer->contact()->attach($contact_id, ['default_status' => 'Y']);
                } else {
                    // Do Nothing
                }
            }
        } else {
            if ($request['contact_new_status'] == 'N') {
                $contact = new Contact;
                $contact->code = HomeController::getMasterCode('contacts', 'Co');
                $contact->honorific = $request['contact_honorific'];
                $contact->name = $request['contact_name'];
                $contact->id_number = $request['contact_id_number'];
                $contact->email = $request['contact_email'];
                $contact->phone = $request['contact_phone'];
                $contact->mobile_phone = $request['contact_mobile_phone'];

                if (!empty($request['contact_birth_date'])) {
                    $contact->birth_date = date('Y-m-d', strtotime($request['contact_birth_date']));
                }

                if ($contact->save()) {
                    $contact_id = $contact->id;
                    $customer = Customer::findOrFail($customer_id);
                    $customer->contact()->attach($contact_id, ['default_status' => 'Y', 'position' => $request['contact_positon'], 'department' => $request['contact_department']]);
                } else {
                    // Do Nothing
                }
            } else {
                if (!empty($request['contact_id'])) {
                    $contact_id = $request['contact_id'];
                }
            }
        }
        return $contact_id;
    }
    public function exportToExcel(Request $request)
    {
        $data['contact'] = Contact::get();
        return Excel::download(new ContactExport($data), 'csv_contact_' . date("YmdHis") . '.xlsx');
    }
}
