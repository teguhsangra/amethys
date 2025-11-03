<?php

namespace App\Exports;

use App\Models\Inquiry;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use DB;
use Carbon\Carbon;

class InquiryExport implements FromView
{

    public function __construct(string $start_date = null, string $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }


    public function view(): View
    {
        libxml_use_internal_errors(true);
        if ($this->start_date != null && $this->end_date != null) {
            $data['inquiry'] = Inquiry::select(
                'inquiries.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'inquiries.created_at',
                'inquiries.posting_by'
            )
                ->join('locations', 'inquiries.location_id', 'locations.id')
                ->join('customers', 'inquiries.customer_id',  'customers.id')
                ->join('contacts', 'inquiries.contact_id',  'contacts.id')
                ->where('inquiries.created_at', '>=', $this->start_date)
                ->where('inquiries.created_at', '<=', $this->end_date)
                ->get();
        } else {
            $data['inquiry'] = Inquiry::select(
                'inquiries.*',
                'locations.name as location_name',
                'customers.name as customer_name',
                'contacts.name as customer_contact_name',
                'customers.phone as customer_phone_no',
                'customers.email as customer_email',
                'inquiries.created_at',
                'inquiries.posting_by'
            )
                ->join('locations', 'inquiries.location_id', '=', 'locations.id')
                ->join('customers', 'inquiries.customer_id', '=', 'customers.id')
                ->join('contacts', 'inquiries.contact_id', '=', 'contacts.id')
                ->get();
        }


        return view('pages.report.inquiry.excel', $data);
    }
}
