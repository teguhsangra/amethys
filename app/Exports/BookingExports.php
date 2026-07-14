<?php

namespace App\Exports;

use App\Models\Booking;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use DB;
use Carbon\Carbon;

class BookingExports implements FromView
{

    public function __construct(string $room_category_id = null, string $start_date = null, string $end_date = null)
    {
        $this->room_category_id = $room_category_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }


    public function view(): View
    {
        libxml_use_internal_errors(true);
        $active_status_id = array(1, 2, 4);

        if ($this->start_date != null && $this->end_date != null) {
            $data['bookings'] = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'bookings.created_at',
                'bookings.posting_by'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->join('customers', 'bookings.customer_id',  'customers.id')
                ->join('contacts', 'bookings.contact_id',  'contacts.id')
                ->where('bookings.is_main_agreement', 'Y')
                ->where('bookings.created_at', '>=', $this->start_date)
                ->where('bookings.created_at', '<=', $this->end_date)
                ->get();
        } else if ($this->room_category_id != null && $this->start_date != null && $this->end_date != null) {
            $data['bookings'] = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'bookings.created_at',
                'bookings.posting_by'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->join('customers', 'bookings.customer_id',  'customers.id')
                ->join('contacts', 'bookings.contact_id',  'contacts.id')
                ->where('bookings.is_main_agreement', 'Y')
                ->where('bookings.room_category_id', $this->room_category_id)
                ->where('bookings.created_at', '>=', $this->start_date)
                ->where('bookings.created_at', '<=', $this->end_date)
                ->get();
        } else {
            $data['bookings'] = Booking::select(
                'bookings.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'bookings.created_at',
                'bookings.posting_by'
            )
                ->join('locations', 'bookings.location_id', 'locations.id')
                ->join('customers', 'bookings.customer_id',  'customers.id')
                ->join('contacts', 'bookings.contact_id',  'contacts.id')
                ->get();
        }


        return view('pages.report.booking.excel', $data);
    }
}
