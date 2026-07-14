<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BookingReminderExport implements FromView
{
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
        return view('pages.transaction.booking_reminder.excel', $this->data);
    }
}
