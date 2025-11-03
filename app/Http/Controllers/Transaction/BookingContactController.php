<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Contact;
use App\Models\Booking;
use DataTables;
use Validator;
use Redirect;
use Auth;
use DB;

class BookingContactController extends Controller
{
    private $url = 'booking_contact';
    private $form_id = 'contact_form';
    private $table_name = 'contacts';
    private $prefix_name = 'Co';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['url'] = $this->url;
        $data['back_url'] = \Request::get('back_url');
        $data['menu_name'] = \Request::get('menu_name');
        $data['booking_id'] = \Request::get('booking_id');
        return view('pages.transaction.booking_contact.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $back_url = \Request::get('back_url');
        $menu_name = \Request::get('menu_name');
        $booking_id = \Request::get('booking_id');

        $this->url = $this->url . '?back_url=' . $back_url . '&menu_name=' . $menu_name . '&booking_id=' . $booking_id;
        $data['url'] = $this->url;
        $data['form_url'] = $this->url;
        $data['form_id'] = $this->table_name;
        $data['method'] = 'POST';
        $data['button_name'] = 'Create';
        $data['code'] = HomeController::getMasterCode($this->table_name, $this->prefix_name);

        return view('pages.transaction.booking_contact.editor', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $back_url = \Request::get('back_url');
        $menu_name = \Request::get('menu_name');
        $booking_id = \Request::get('booking_id');

        $booking = Booking::findOrFail($booking_id);

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:contacts',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create?back_url=' . $back_url . '&menu_name=' . $menu_name . '&booking_id=' . $booking_id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->url = $this->url . '?back_url=' . $back_url . '&menu_name=' . $menu_name . '&booking_id=' . $booking_id;

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
                $contact->customer()->attach($booking->customer_id, ['default_status' => 'N', 'position' => $request['position'], 'department' => $request['department']]);
                $booking->contacts()->attach($contact->id, ['position' => $request['position']]);
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
        return Contact::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $back_url = \Request::get('back_url');
        $menu_name = \Request::get('menu_name');
        $booking_id = \Request::get('booking_id');

        $booking = Booking::findOrFail($booking_id);

        $data['url'] = $this->url . '?back_url=' . $back_url . '&menu_name=' . $menu_name . '&booking_id=' . $booking_id;
        $data['form_url'] = $this->url . '/' . $id . '?back_url=' . $back_url . '&menu_name=' . $menu_name . '&booking_id=' . $booking_id;
        $data['form_id'] = $this->table_name;
        $data['method'] = 'PUT';
        $data['button_name'] = 'Update';
        $data['contact'] = Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
            ->select('contacts.*', 'customer_and_contact.position', 'customer_and_contact.department')
            ->where('contacts.id', $id)
            ->where('customer_and_contact.customer_id', $booking->customer_id)
            ->first();

        return view('pages.transaction.booking_contact.editor', $data);
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
        $back_url = \Request::get('back_url');
        $menu_name = \Request::get('menu_name');
        $booking_id = \Request::get('booking_id');

        $booking = Booking::findOrFail($booking_id);

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:contacts,code,' . $id,
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create?back_url=' . $back_url . '&menu_name=' . $menu_name . '&booking_id=' . $booking_id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->url = $this->url . '?back_url=' . $back_url . '&menu_name=' . $menu_name . '&booking_id=' . $booking_id;

            $contact = Contact::findOrFail($id);
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
                DB::table('booking_and_contact')->where('contact_id', $id)->where('booking_id', $booking->id)->delete();
                DB::table('customer_and_contact')->where('contact_id', $id)->where('customer_id', $booking->customer_id)->delete();

                $contact->customer()->attach($booking->customer_id, ['default_status' => 'N', 'position' => $request['position'], 'department' => $request['department']]);
                $booking->contacts()->attach($contact->id, ['position' => $request['position']]);
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
        //
    }

    public function datatables()
    {
        $id = \Request::get('booking_id');
        $booking = Booking::findOrFail($id);

        $contacts = Contact::join('customer_and_contact', 'customer_and_contact.contact_id', 'contacts.id')
            ->where('customer_and_contact.customer_id', $booking->customer_id)
            ->select('contacts.*', 'customer_and_contact.position', 'customer_and_contact.department')
            ->get();

        return DataTables::of($contacts)->make(true);
    }
}
