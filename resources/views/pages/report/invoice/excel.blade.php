<table  class="table table-border table-hover">
    <thead>
        <tr>
            <th>Code</th>
            <th>Customer</th>
            <th>Total Price</th>
            <th>Total Service Price</th>
            <th>Tax Price</th>
            <th>Total Paid</th>
            <th>Total Outstanding</th>
            <th>Invoice Status</th>
            <th>Invoice Date</th>
            <th>Due Date</th>
            <th>Start Period</th>
            <th>End Period</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr>
            <td>{{ $invoice->code }}</td>
            <td>{{ $invoice->customer_name }}</td>
            <td>{{ $invoice->total_price }}</td>
            <td>{{ $invoice->total_service_charge }}</td>
            <td>{{ $invoice->total_tax_price }}</td>
            <td>{{ $invoice->total_paid }}</td>
            <td>{{ $invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price - $invoice->total_paid }}</td>
            <td>{{ $invoice->payment_status }}</td>
            <td>{{ $invoice->invoice_date }}</td>
            <td>{{ $invoice->due_date }}</td>
            <td>
                @php
                    $return = "";

                    if(!empty($invoice->invoice_detail)){
                        foreach($invoice->invoice_detail as $invoice_detail){
                            if(!empty($invoice_detail->booking_detail)){
                                if($invoice_detail->booking_detail->booking->is_main_agreement == "Y"){
                                    $return = date('j M Y', strtotime($invoice_detail->booking_detail->start_date));
                                    break;
                                }else{
                                    break;
                                }
                            }else{
                                break;
                            }
                        }
                    }
                @endphp
                {{ $return }}
            </td>
            <td>
                @php
                    $return = "";

                    if(!empty($invoice->invoice_detail)){
                        foreach($invoice->invoice_detail as $no => $invoice_detail){
                            if(!empty($invoice_detail->booking_detail)){
                                if($invoice_detail->booking_detail->booking->is_main_agreement == "Y"){
                                    $return = date('j M Y', strtotime($invoice_detail->booking_detail->end_date));
                                }else{
                                    break;
                                }
                            }else{
                                break;
                            }
                        }
                    }
                @endphp
                {{ $return }}
            </td>
            <td>
                @php
                    $return = "";
                    $current_word = "";
                    $last_word = "";

                    if(!empty($invoice->invoice_detail)){
                        foreach($invoice->invoice_detail as $no => $invoice_detail){
                            if(!empty($invoice_detail->booking_detail)){
                                if(!empty($invoice_detail->booking_detail->booking->room_category)){
                                    $current_word = $invoice_detail->booking_detail->booking->room_category->code;
                                }
                                if(!empty($invoice_detail->booking_detail->product)){
                                    $current_word = $invoice_detail->booking_detail->product->name;
                                }
                            }elseif(!empty($invoice_detail->order_detail)){
                                $current_word = "POS";
                            }else{
                                // Do Nothing
                            }
                        }
                    }
                    if(!empty($invoice->booking)){
                        if(!empty($invoice->booking->room_category)){
                            $current_word = $invoice->booking->room_category->code;
                        }
                        if(!empty($invoice->booking->product)){
                            $current_word = $invoice->booking->product->name;
                        }
                    }

                    if($last_word != $current_word){
                        $return = $return." ".$current_word;
                        $last_word = $current_word;
                    }
                @endphp
                {{ $return }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
