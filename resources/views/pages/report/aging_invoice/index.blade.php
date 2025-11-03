@extends('layouts.app')
@section('title')
Rakomsis Invoice Aging Report
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-3 "><b>Choose Days</b></label>
                            <br>
                            <select id="days" data-live-search="true" class="form-control selectpicker" data-style="btn btn-primary btn-round" data-show-subtext="true">
                                <option value="ALL">All</option>
                                <option value="30">1 - 30 Days</option>
                                <option value="60">31 - 60 Days</option>
                                <option value="90">61 - 90 Days</option>
                                <option value="91">91 ++ Days</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" id="all_table">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">ALL</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="all-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Invoice Due Date</th>
                                <th>Amount Outstanding</th>
                                <th>Current</th>
                                <th>Aged 1 - 30</th>
                                <th>Aged 31 - 60</th>
                                <th>Aged 61 - 90</th>
                                <th>Aged > 91</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <?php
                                    $now = Carbon\Carbon::now();
                                    $due_date = Carbon\Carbon::parse($invoice->due_date);

                                    $diff = $now->diffInDays($due_date);
                                    $total_outstanding = $invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price - $invoice->total_paid;

                                    if($due_date >= $now){
                                        $diff = 0;
                                    }
                                ?>
                                <tr>
                                    <td>{{ $invoice->customer->name }}</td>
                                    <td>{{ $invoice->code }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->invoice_date)) }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->due_date)) }}</td>
                                    <td>{{ number_format($total_outstanding, 0,',','.') }}</td>
                                    <td>
                                        @if($diff == 0)
                                            {{ number_format($total_outstanding, 0,',','.') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($diff >= 1 && $diff <= 30)
                                            {{ number_format($total_outstanding, 0,',','.') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($diff >= 31 && $diff <= 60)
                                            {{ number_format($total_outstanding, 0,',','.') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($diff >= 61 && $diff <= 90)
                                            {{ number_format($total_outstanding, 0,',','.') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($diff >= 91)
                                            {{ number_format($total_outstanding, 0,',','.') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" id="30_table" style="display:none;">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">1 - 30 Days</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="30-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Total Ageing</th>
                                <th>Total</th>
                                <th>Tax Price</th>
                                <th>Tax Outstanding</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <?php
                                    $now = Carbon\Carbon::now();
                                    $due_date = Carbon\Carbon::parse($invoice->due_date);

                                    $diff =  $now->diffInDays($due_date);
                                    $total_outstanding = $invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price - $invoice->total_paid;
                                    
                                    if($due_date >= $now){
                                        $diff = 0;
                                    }
                                ?>
                                @if($diff >= 1 && $diff <=30)
                                <tr>
                                    <td>{{ $invoice->code }}</td>
                                    <td>{{ $invoice->customer->name }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->invoice_date)) }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->due_date)) }}</td>
                                    <td>{{ number_format($diff, 0,',','.') }}</td>
                                    <td>{{ number_format($invoice->total_price, 0,',','.') }}</td>
                                    <td>{{ number_format($invoice->total_tax_price, 0,',','.') }}</td>
                                    <td>{{ number_format($total_outstanding, 0,',','.') }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12" id="60_table" style="display:none;">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">31 - 60 Days</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="60-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Total Ageing</th>
                                <th>Total</th>
                                <th>Tax Price</th>
                                <th>Tax Outstanding</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <?php
                                    $now = Carbon\Carbon::now();
                                    $due_date = Carbon\Carbon::parse($invoice->due_date);

                                    $diff =  $now->diffInDays($due_date);
                                    $total_outstanding = $invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price - $invoice->total_paid;
                                    
                                    if($due_date >= $now){
                                        $diff = 0;
                                    }
                                ?>
                                @if($diff >= 31 && $diff <=60)
                                <tr>
                                    <td>{{ $invoice->code }}</td>
                                    <td>{{ $invoice->customer->name }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->invoice_date)) }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->due_date)) }}</td>
                                    <td>{{ number_format($diff, 0,',','.') }}</td>
                                    <td>{{ number_format($invoice->total_price, 0,',','.') }}</td>
                                    <td>{{ number_format($invoice->total_tax_price, 0,',','.') }}</td>
                                    <td>{{ number_format($total_outstanding, 0,',','.') }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" id="90_table" style="display:none;">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">61 - 90 Days</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="90-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Total Ageing</th>
                                <th>Total</th>
                                <th>Tax Price</th>
                                <th>Tax Outstanding</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <?php
                                    $now = Carbon\Carbon::now();
                                    $due_date = Carbon\Carbon::parse($invoice->due_date);

                                    $diff =  $now->diffInDays($due_date);
                                    $total_outstanding = $invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price - $invoice->total_paid;
                                    
                                    if($due_date >= $now){
                                        $diff = 0;
                                    }
                                ?>
                                @if($diff >= 61 && $diff <=90)
                                <tr>
                                    <td>{{ $invoice->code }}</td>
                                    <td>{{ $invoice->customer->name }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->invoice_date)) }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->due_date)) }}</td>
                                    <td>{{ number_format($diff, 0,',','.') }}</td>
                                    <td>{{ number_format($invoice->total_price, 0,',','.') }}</td>
                                    <td>{{ number_format($invoice->total_tax_price, 0,',','.') }}</td>
                                    <td>{{ number_format($total_outstanding, 0,',','.') }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" id="91_table" style="display:none;">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">91 ++ Days</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="91-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Total Ageing</th>
                                <th>Total</th>
                                <th>Tax Price</th>
                                <th>Tax Outstanding</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <?php
                                    $now = Carbon\Carbon::now();
                                    $due_date = Carbon\Carbon::parse($invoice->due_date);

                                    $diff =  $now->diffInDays($due_date);
                                    $total_outstanding = $invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price - $invoice->total_paid;
                                    
                                    if($due_date >= $now){
                                        $diff = 0;
                                    }
                                ?>
                                @if($diff >= 91)
                                <tr>
                                    <td>{{ $invoice->code }}</td>
                                    <td>{{ $invoice->customer->name }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->invoice_date)) }}</td>
                                    <td>{{ date("d M Y", strtotime($invoice->due_date)) }}</td>
                                    <td>{{ number_format($diff, 0,',','.') }}</td>
                                    <td>{{ number_format($invoice->total_price, 0,',','.') }}</td>
                                    <td>{{ number_format($invoice->total_tax_price, 0,',','.') }}</td>
                                    <td>{{ number_format($total_outstanding, 0,',','.') }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



</div>

{{-- <div id="penaltyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmation Box</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => url(''), 'method' => 'POST', 'id' => 'createForm', 'name' => 'createForm')) }}
                    <input type="hidden" name="invoice_id" id="invoices_id" value="">
                    <input type="hidden" name="total_price" id="invoice_total_price">
                    <input type="hidden" name="due_date" id="due_date">
                    <input type="hidden" name="quantity" id="quantity">
                    <input type="hidden" name="detail_price" id="detail_price">
                    <input type="hidden" name="detail_tax" id="detail_tax">
                    <div class="form-group">
                        <label>Chose Your Date</label>
                        <input class="form-control datepicker" type="text" name="selected_date_of_penalty" value="{{date('Y-m-d')}}" onchange="calculatePenalty(null, null, this.value)">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Sub Total Penalty Price</label><br>
                        <input class="form-control" type="text" name="format_penalty_invoice" id="format_penalty_invoice" disabled>
                        <input type="hidden" name="penalty_invoice" id="penalty_invoice">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Total Tax</label><br>
                        <input class="form-control" type="text" name="format_penalty_tax" id="format_penalty_tax" disabled>
                        <input type="hidden" name="penalty_tax" id="penalty_tax">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Grand Total</label><br>
                        <input class="form-control" type="text" name="format_grand_total" id="format_grand_total" disabled>
                        <input type="hidden" name="grand_total" id="grand_total">
                    </div>
                    {{ Form::close() }}
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                <button type="submit" class="btn btn-success btn-link" onclick="submitForm('createForm')">Yes
                    <div class="ripple-container"></div>
                </button>
            </div>
        </div>
    </div>
</div> --}}

@endsection

@section('js')
<script>
    $(function() {


        var all = $("#all-table").DataTable({

            "lengthMenu": [[-1, 10, 50, 100], ["All", 10, 50, 100]]
        });
        var tree = $("#30-table").DataTable({

            "lengthMenu": [[-1, 10, 50, 100], ["All", 10, 50, 100]]
        });
        var six = $("#60-table").DataTable({

            "lengthMenu": [[-1, 10, 50, 100], ["All", 10, 50, 100]]
        });
        var nine = $("#90-table").DataTable({

            "lengthMenu": [[-1, 10, 50, 100], ["All", 10, 50, 100]]
        });
        var nineone = $("#91-table").DataTable({

            "lengthMenu": [[-1, 10, 50, 100], ["All", 10, 50, 100]]
        });


        $("#days").change(function() {
            var days = document.getElementById("days").value;
            if(days == ''){
                $("#all_table").show();
                $("#30_table").hide();
                $("#60_table").hide();
                $("#90_table").hide();
                $("#91_table").hide();


            }else if(days == "ALL"){
                $("#all_table").show();
                $("#30_table").hide();
                $("#60_table").hide();
                $("#90_table").hide();
                $("#91_table").hide();


            }else if(days == 30){
                $("#all_table").hide();
                $("#30_table").show();
                $("#60_table").hide();
                $("#90_table").hide();
                $("#91_table").hide();

            }else if(days == 60){
                $("#all_table").hide();
                $("#30_table").hide();
                $("#60_table").show();
                $("#90_table").hide();
                $("#91_table").hide();

            }else if(days == 90){
                $("#all_table").hide();
                $("#30_table").hide();
                $("#60_table").hide();
                $("#90_table").show();
                $("#91_table").hide();

            }else if(days == 91){
                $("#all_table").hide();
                $("#30_table").hide();
                $("#60_table").hide();
                $("#90_table").hide();
                $("#91_table").show();

            }else{
                $("#all_table").show();
                $("#30_table").hide();
                $("#60_table").hide();
                $("#90_table").hide();
                $("#91_table").hide();

            }
        });

	});

    function exportExcel(){
        var days = document.getElementById("days").value;
        var url = '{{ url('exportAgingInvoice') }}'+"?days="+days;

        var link =url;
        window.location =link;
        return false;
    }
</script>
@endsection
