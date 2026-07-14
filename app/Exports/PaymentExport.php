<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentExport implements FromView
{

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
        return view('pages.report.payment.excel', $this->data);
    }
}
