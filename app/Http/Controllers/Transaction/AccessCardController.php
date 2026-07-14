<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Transaction\SalesActivityController;
use App\Models\ParameterSetting;
use App\Models\Status;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\AccessCard;
use App\Models\HistoryAccessCard;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class AccessCardController extends Controller
{

    private $url = 'access_card_transaction';
    private $form_id = 'access_card_transaction_form';
    private $table_name = 'access_cards';
    private $prefix_name = 'AC';

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
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['url'] = $this->url;
        $data['a_g_and_module'] = $a_g_and_module;
        $data['employee'] = $employee;
        return view('pages.transaction.access_card.index', $data);
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

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['contact'] = Contact::get();
        $data['card'] = AccessCard::where('access_cards.customer_id', null)
            ->where('access_cards.contact_id', null)
            ->select('access_cards.*')
            ->get();

        return view('pages.transaction.access_card.editor', $data);
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
            'location_id' => 'required',
            'access_card_id' => 'required',
            'customer_id' => 'required',
            'activity' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $histoy = new HistoryAccessCard;
            $histoy->user_id = Auth::user()->id;
            $histoy->access_card_id = $request['access_card_id'];
            $histoy->customer_id = $request['customer_id'];
            $histoy->contact_id = $request['contact_id'];
            $histoy->activity = $request['activity'];
            $histoy->remarks = $request['remarks'];
            
            if ($histoy->save()) {
                $access = AccessCard::findOrFail($request['access_card_id']);
                $access->location_id = $request['location_id'];
                $access->customer_id = null;
                $access->contact_id = null;
                $access->is_lost = "N";
                switch($histoy->activity){
                    case "activation":
                        $access->customer_id = $request['customer_id'];
                        $access->contact_id = $request['contact_id'];
                    break;
                    case "deactivation":

                    break;
                    case "missing":
                        $access->is_lost = "Y";
                    break;
                    case "defective":
                        $access->is_defective = "Y";
                    break;
                }
                $access->remarks = $request['remarks'];
                $access->save();
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

        $a_g_and_module = HomeController::getAccess($this->url);
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($a_g_and_module == null || $employee == null) {
            \Session::flash('error', 'You are not allowed to access this module !!!');
            return Redirect::to('profile');
        }

        $data['a_g_and_module'] = $a_g_and_module;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url . '/' . $id;
        $data['form_id'] = $this->form_id;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['locations'] = Auth::user()->location;
        $data['customers'] = Customer::get();
        $data['contact'] = Contact::get();
        $data['card'] = AccessCard::leftJoin('history_access_cards', 'history_access_cards.access_card_id', 'access_cards.id')
            ->where('access_cards.customer_id', null)
            ->where('access_cards.contact_id', null)
            ->select('access_cards.*')
            ->get();

        $data['access_card'] = AccessCard::leftJoin('history_access_cards', 'history_access_cards.access_card_id', 'access_cards.id')
            ->where('history_access_cards.access_card_id', $id)
            ->select('access_cards.*', 'history_access_cards.activity', 'history_access_cards.remarks')
            ->first();
        return view('pages.transaction.access_card.editor', $data);
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
        $access_card = HistoryAccessCard::where('access_card_id', $id)->first();
        $access_card->customer_id = $request['customer_id'];
        $access_card->contact_id = $request['contact_id'];
        $access_card->activity = $request['activity'];
        $access_card->remarks = $request['remarks'];

        if ($access_card->save()) {
            if ($access_card->activity == "missing" || $access_card->activity == "defective") {
                $card = AccessCard::findOrFail($id);
                $card->is_lost == "Y";
                if (!$card->save()) {
                    DB::rollBack();
                    \Session::flash('error', 'You are failed in updating your data !!!');
                }
            } else {
                $card = AccessCard::findOrFail($id);
                $card->customer_id = $request['customer_id'];
                $card->contact_id = $request['contact_id'];
                if (!$card->save()) {
                    DB::rollBack();
                    \Session::flash('error', 'You are failed in updating your data !!!');
                }
            }
            DB::commit();
            \Session::flash('success', 'You are success in updating your data');
        } else {
            DB::rollBack();
            \Session::flash('error', 'You are failed in updating your data !!!');
        }
        return Redirect::to($this->url);
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

    public function datatables()
    {
        $access = AccessCard::join('history_access_cards', 'history_access_cards.access_card_id', 'access_cards.id')
            ->leftJoin('customers', 'customers.id', 'access_cards.customer_id')
            ->leftJoin('contacts', 'contacts.id', 'access_cards.contact_id')
            ->leftJoin('locations', 'locations.id', 'access_cards.location_id')
            ->select('access_cards.*', 'locations.name as location', 'customers.name as customer', 'contacts.name as contact', 'history_access_cards.remarks')
            ->where('history_access_cards.activity', \Request::get('activity'))
            ->where('access_cards.is_lost', \Request::get('is_lost'))
            ->where('access_cards.is_defective', \Request::get('is_defective'))
            ->get();

        return DataTables::of($access)->make(true);
    }
}
