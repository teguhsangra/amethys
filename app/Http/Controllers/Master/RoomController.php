<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\ParameterSetting;
use App\Models\Location;
use App\Models\Room;
use App\Models\RoomPhoto;
use App\Models\RoomType;
use App\Models\RoomCategory;
use App\Models\Booking;
use App\Models\Furniture;
use DataTables;
use Validator;
use Redirect;
use Image;
use File;
use Auth;
use DB;

class RoomController extends Controller
{
    private $url = 'room';
    private $form_id = 'room_form';
    private $table_name = 'rooms';
    private $prefix_name = 'Ro';
    private $destinationPath = '/uploads/room/';
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
        return view('pages.master.room.index', $data);
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
        $data['room_categories'] = RoomCategory::get();
        $data['room_types'] = RoomType::get();
        $data['locations'] = Location::get();
        $data['furniture'] = Furniture::all();
        return view('pages.master.room.editor', $data);
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
            'code' => 'required|unique:rooms',
            'room_category_id' => 'required',
            'location_id' => 'required',
            'room_number' => 'required',
            'sqm' => 'numeric',
            'number_of_workstation' => 'integer'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $room = new Room;

            $parent_id = null;
            if (!empty($request['parent_id'])) {
                $parent_id = $request['parent_id'];
            }
            $room->parent_id = $request['parent_id'];
            $room->room_type_id = $request['room_type_id'];
            $room->location_id = $request['location_id'];
            $room->code = $request['code'];
            $room->room_number = $request['room_number'];
            $room->monthly_price = $request['monthly_price'];
            $room->daily_price = $request['daily_price'];
            $room->halfday_price = $request['halfday_price'];
            $room->daily_exclude_breakfast_price = $request['daily_exclude_breakfast_price'];
            $room->hourly_price = $request['hourly_price'];
            $room->after_office_hourly_price = $request['after_office_hourly_price'];
            $room->holiday_hourly_price = $request['holiday_hourly_price'];
            $room->sqm = $request['sqm'];
            $room->number_of_workstation = $request['number_of_workstation'];
            $room->has_service_charge = $request['has_service_charge'];
            $room->is_editable_price = $request['is_editable_price'];
            $room->created_by = Auth::user()->name;
            if ($room->save()) {
                for ($i = 0; $i < sizeof($request['room_category_id']); $i++) {
                    $room->room_category()->attach($request['room_category_id'][$i]);
                }
                for ($i = 0; $i < sizeof($request['furniture_id']); $i++) {
                    $room->furniture()->attach($request['furniture_id'][$i], ['quantity' => $request['fu_quantity'][$i]]);
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
        $data['room'] = Room::findOrFail($id);
        return view('pages.master.room.detail', $data);
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
        $data['room'] = Room::findOrFail($id);
        $data['room_categories'] = RoomCategory::get();
        $data['room_types'] = RoomType::get();
        $data['locations'] = Location::get();
        $data['furniture'] = Furniture::all();
        return view('pages.master.room.editor', $data);
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
        $room = Room::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:rooms,code,' . $room->id,
            'room_category_id' => 'required',
            'location_id' => 'required',
            'room_number' => 'required',
            'sqm' => 'numeric',
            'number_of_workstation' => 'integer'
        ]);

        if ($validator->fails()) {
            return redirect($this->url . '/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $parent_id = null;
            if (!empty($request['parent_id'])) {
                $parent_id = $request['parent_id'];
            }
            $room->parent_id = $request['parent_id'];
            $room->room_type_id = $request['room_type_id'];
            $room->location_id = $request['location_id'];
            $room->code = $request['code'];
            $room->room_number = $request['room_number'];
            $room->monthly_price = $request['monthly_price'];
            $room->daily_price = $request['daily_price'];
            $room->halfday_price = $request['halfday_price'];
            $room->daily_exclude_breakfast_price = $request['daily_exclude_breakfast_price'];
            $room->hourly_price = $request['hourly_price'];
            $room->after_office_hourly_price = $request['after_office_hourly_price'];
            $room->holiday_hourly_price = $request['holiday_hourly_price'];
            $room->sqm = $request['sqm'];
            $room->number_of_workstation = $request['number_of_workstation'];
            $room->has_service_charge = $request['has_service_charge'];
            $room->is_editable_price = $request['is_editable_price'];
            $room->created_by = Auth::user()->name;
            if ($room->save()) {
                DB::table('r_c_and_room')->where('room_id', $id)->delete();
                for ($i = 0; $i < sizeof($request['room_category_id']); $i++) {
                    $room->room_category()->attach($request['room_category_id'][$i]);
                }
                if (!empty($request['furniture_id'])) {
                    DB::table('room_and_furniture')->where('room_id', $id)->delete();
                    for ($i = 0; $i < sizeof($request['furniture_id']); $i++) {
                        $room->furniture()->attach($request['furniture_id'][$i], ['quantity' => $request['fu_quantity'][$i]]);
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
        $room = Room::findOrFail($id);
        if ($room->delete()) {
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
        $data['room'] = Room::findOrFail($id);
        return view('pages.master.room.photo', $data);
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
            $room = Room::findOrFail($id);
            $room_photo = new RoomPhoto;
            $room_photo->room_id = $id;

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
                $room_photo->photo = $this->destinationPath . '' . $photoName;

                if (sizeof(RoomPhoto::where('room_id', $id)->get()) == 0) {
                    $room_photo->default = "Y";
                    $room->default_photo = $room_photo->photo;
                    $room->save();
                }
            }

            if ($room_photo->save()) {
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
        $room_photo = RoomPhoto::findOrFail($id);
        $room = $room_photo->room;
        if (sizeof(RoomPhoto::where('room_id', $room->id)->get()) > 1) {
            if ($room_photo->default == "Y" && $request['default_status'] == "N") {
                $other_room_photo = RoomPhoto::where('id', '!=', $id)->first();
                $other_room_photo->default = "Y";
                $other_room_photo->save();
            }
            if ($room_photo->default == "N" && $request['default_status'] == "Y") {
                $other_room_photo = RoomPhoto::where('room_id', $room->id)->where('default', 'Y')->first();
                $other_room_photo->default = "N";
                $other_room_photo->save();
            }

            $room_photo->default = $request['default_status'];
            $room_photo->save();

            $default_room_photo = RoomPhoto::where('room_id', $room->id)->where('default', 'Y')->first();
            $room->default_photo = $default_room_photo->photo;
            $room->save();
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
        $room_photo = RoomPhoto::findOrFail($id);
        $room = $room_photo->room;
        if ($room_photo->delete()) {
            if ($this->main_path == "local") {
                File::Delete(public_path($room_photo->photo));
            } else {
                File::Delete($this->main_path . $room_photo->photo);
            }
            \Session::flash('success', 'You are success in deleting your data');
        } else {
            \Session::flash('error', 'You are failed in deleting your data !!!');
        }
        return Redirect::to($this->url . '/photo/' . $room_photo->id);
    }

    public function get_by_id($id)
    {
        $room = Room::findOrFail($id);
        $room['furniture'] = $room->furniture;

        return $room;
    }

    public function get_by_location_id(Request $request, $location_id)
    {
        $room_category_id = $request['room_category_id'];

        return Room::join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->where('rooms.location_id', $location_id)
            ->where('r_c_and_room.room_category_id', $room_category_id)
            ->get();
    }

    public function get_by_transaction(Request $request, $location_id)
    {
        $active_status_id = array(1, 2);
        $room_ids = array();
        $start_date = date('Y-m-d', strtotime($request['start_date']));
        $end_date = date('Y-m-d', strtotime($request['end_date']));
        $start_time = date('H:i:s', strtotime(date('Y-m-d')." ".$request['start_time'] ));
        $end_time = date('H:i:s', strtotime(date('Y-m-d')." ".$request['end_time'] ));
        
        if (!empty($request['booking_id'])) {
            $first_booking_details = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                ->where('start_date', '<', $end_date)
                ->where('end_date', '>', $start_date)
                ->where('type', 'room')
                ->where('booking_and_room.booking_id', '!=', $request['booking_id'])
                ->whereIn('status_id', $active_status_id)
                ->select('booking_and_room.room_id')
                ->get();
        } else {
            $first_booking_details = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                ->where('start_date', '<', $end_date)
                ->where('end_date', '>', $start_date)
                ->where('type', 'room')
                ->whereIn('status_id', $active_status_id)
                ->select('booking_and_room.room_id')
                ->get();
        }

        foreach ($first_booking_details as $detail) {
            array_push($room_ids, $detail->room_id);
        }

        if (($start_time != '' || $start_time != null) && ($end_time != '' || $end_time != null)) {
            if (!empty($request['booking_id'])) {
                $second_booking_details = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                    ->where('start_date', $end_date)
                    ->where('start_time', '<', $end_time)
                    ->where('end_time', '>', $start_time)
                    ->where('type', 'room')
                    ->where('booking_and_room.booking_id', '!=', $request['booking_id'])
                    ->whereIn('status_id', $active_status_id)
                    ->select('booking_and_room.room_id')
                    ->get();
            } else {
                $second_booking_details = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                    ->where('start_date', $end_date)
                    ->where('start_time', '<', $end_time)
                    ->where('end_time', '>', $start_time)
                    ->where('type', 'room')
                    ->whereIn('status_id', $active_status_id)
                    ->select('booking_and_room.room_id')
                    ->get();
            }

            foreach ($second_booking_details as $detail) {
                array_push($room_ids, $detail->room_id);
            }

            if (!empty($request['booking_id'])) {
                $third_booking_details = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                    ->where('start_date', $end_date)
                    ->where('start_time', '<', $end_time)
                    ->where('end_time', '>', $start_time)
                    ->where('type', 'room')
                    ->where('booking_and_room.booking_id', '!=', $request['booking_id'])
                    ->whereIn('status_id', $active_status_id)
                    ->select('booking_and_room.room_id')
                    ->get();
            } else {
                $third_booking_details = Booking::join('booking_and_room', 'booking_and_room.booking_id', 'bookings.id')
                    ->where('start_date', $end_date)
                    ->where('start_time', '<', $end_time)
                    ->where('end_time', '>', $start_time)
                    ->where('type', 'room')
                    ->whereIn('status_id', $active_status_id)
                    ->select('booking_and_room.room_id')
                    ->get();
            }

            foreach ($third_booking_details as $detail) {
                array_push($room_ids, $detail->room_id);
            }
        }

        return Room::join('r_c_and_room', 'r_c_and_room.room_id', 'rooms.id')
            ->where('r_c_and_room.room_category_id', $request['room_category_id'])
            ->where('location_id', $location_id)
            ->whereNotIn('id', $room_ids)
            ->get();
    }

    public function datatables()
    {
        $rooms = Room::join('locations', 'locations.id', 'rooms.location_id')
            ->leftJoin('room_types', 'room_types.id', 'rooms.room_type_id')
            ->select('rooms.*', 'locations.name as location_name', 'room_types.name as room_type_name')
            ->get();

        return DataTables::of($rooms)->make(true);
    }

    public function datatables_photo($id)
    {
        $room_photos = RoomPhoto::where('room_id', $id)->get();

        return DataTables::of($room_photos)->make(true);
    }
}
